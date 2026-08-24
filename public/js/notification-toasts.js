(function () {
    var config = window.AppNotificationToastsConfig;
    if (!config || !config.userId) {
        return;
    }

    var MAX_TOASTS = 3;
    var DURATION_MS = 4000;
    var POLL_MS = 18000;
    var storageKey = 'notif_toast_seen_user_' + config.userId;
    var seeded = false;
    var activeTimers = {};

    function seenIds() {
        try {
            var raw = sessionStorage.getItem(storageKey);
            var parsed = raw ? JSON.parse(raw) : [];
            return Array.isArray(parsed) ? parsed.map(String) : [];
        } catch (e) {
            return [];
        }
    }

    function saveSeen(ids) {
        sessionStorage.setItem(storageKey, JSON.stringify(ids));
    }

    function markSeen(id) {
        var ids = seenIds();
        id = String(id);
        if (ids.indexOf(id) === -1) {
            ids.push(id);
            saveSeen(ids);
        }
    }

    function redirectOf(n) {
        if (!n) {
            return '';
        }
        if (n.data && n.data.redirect) {
            return n.data.redirect;
        }
        return '';
    }

    function escapeHtml(str) {
        return String(str == null ? '' : str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function container() {
        return document.getElementById('appNotificationToasts');
    }

    function dismissToast(el, id) {
        if (!el || el.dataset.leaving === '1') {
            return;
        }
        el.dataset.leaving = '1';
        if (activeTimers[id]) {
            clearTimeout(activeTimers[id]);
            delete activeTimers[id];
        }
        el.style.transition = 'transform 0.35s ease, opacity 0.35s ease';
        el.style.transform = 'translateX(120%)';
        el.style.opacity = '0';
        setTimeout(function () {
            if (el.parentNode) {
                el.parentNode.removeChild(el);
            }
        }, 360);
    }

    function startTimer(el, id) {
        if (activeTimers[id]) {
            clearTimeout(activeTimers[id]);
        }
        activeTimers[id] = setTimeout(function () {
            dismissToast(el, id);
        }, DURATION_MS);
    }

    function markRead(id, redirectUrl) {
        var url = config.readUrlTemplate.replace('__ID__', encodeURIComponent(id));
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': config.csrf,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        }).finally(function () {
            if (redirectUrl) {
                window.location.href = redirectUrl;
            }
        });
    }

    function showToast(n) {
        var wrap = container();
        if (!wrap) {
            return;
        }

        while (wrap.children.length >= MAX_TOASTS) {
            wrap.removeChild(wrap.lastChild);
        }

        var id = String(n.id);
        var el = document.createElement('div');
        el.className = 'pointer-events-auto w-full rounded-2xl border border-blue-100 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-lg shadow-slate-900/10 overflow-hidden';
        el.setAttribute('role', 'status');
        el.style.transform = 'translateX(120%)';
        el.style.opacity = '0';
        el.style.transition = 'transform 0.35s ease, opacity 0.35s ease';

        var title = escapeHtml(n.title || '');
        var message = escapeHtml(n.message || '');
        var href = redirectOf(n);

        el.innerHTML =
            '<div class="flex gap-3 p-4 cursor-pointer" data-toast-body="1">' +
                '<div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-slate-800 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">' +
                    '<i class="fa-regular fa-bell"></i>' +
                '</div>' +
                '<div class="min-w-0 flex-1">' +
                    '<p class="text-[11px] font-bold uppercase tracking-wide text-blue-500 dark:text-blue-400">Notification</p>' +
                    '<p class="text-sm font-bold text-slate-800 dark:text-white mt-0.5 truncate">' + title + '</p>' +
                    '<p class="text-xs text-slate-500 dark:text-slate-400 mt-1 line-clamp-2">' + message + '</p>' +
                '</div>' +
                '<button type="button" data-toast-close="1" class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-700 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 shrink-0" aria-label="Close">' +
                    '<i class="fa-solid fa-xmark text-xs"></i>' +
                '</button>' +
            '</div>';

        wrap.insertBefore(el, wrap.firstChild);

        requestAnimationFrame(function () {
            el.style.transform = 'translateX(0)';
            el.style.opacity = '1';
        });

        el.querySelector('[data-toast-close]').addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            dismissToast(el, id);
        });

        el.querySelector('[data-toast-body]').addEventListener('click', function (e) {
            if (e.target.closest('[data-toast-close]')) {
                return;
            }
            markRead(id, href || null);
        });

        el.addEventListener('mouseenter', function () {
            if (activeTimers[id]) {
                clearTimeout(activeTimers[id]);
                delete activeTimers[id];
            }
        });
        el.addEventListener('mouseleave', function () {
            startTimer(el, id);
        });

        startTimer(el, id);
    }

    window.AppNotificationToasts = {
        handle: function (notifications, options) {
            options = options || {};
            var list = Array.isArray(notifications) ? notifications : [];

            if (options.seed || !seeded) {
                list.forEach(function (n) {
                    if (n && n.id != null) {
                        markSeen(n.id);
                    }
                });
                seeded = true;
                return;
            }

            list.forEach(function (n) {
                if (!n || n.id == null) {
                    return;
                }
                if (n.is_read) {
                    markSeen(n.id);
                    return;
                }
                var id = String(n.id);
                if (seenIds().indexOf(id) !== -1) {
                    return;
                }
                markSeen(id);
                showToast(n);
            });
        }
    };

    function poll() {
        fetch(config.indexUrl, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(function (res) { return res.ok ? res.json() : Promise.reject(); })
            .then(function (data) {
                window.AppNotificationToasts.handle(data.notifications || [], { seed: !seeded });
                window.dispatchEvent(new CustomEvent('app:notifications', { detail: data }));
            })
            .catch(function () {});
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', poll);
    } else {
        poll();
    }
    setInterval(poll, POLL_MS);
})();
