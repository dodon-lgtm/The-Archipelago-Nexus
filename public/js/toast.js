/**
 * Global Toast Notification (pengganti alert())
 *
 * Usage:
 *   showToast('Pesan berhasil.', 'success');
 *   showToast('Terjadi kesalahan.', 'error');
 */
(function () {
    if (window.showToast) {
        return;
    }

    var DURATION_MS = 4000;
    var FADE_MS = 350;
    var MAX_TOASTS = 4;

    var style = document.createElement('style');
    style.textContent =
        '#globalToastContainer{position:fixed;top:1.25rem;right:1.25rem;z-index:9999;display:flex;flex-direction:column;gap:.75rem;pointer-events:none;max-width:min(22rem,calc(100vw - 2.5rem));font-family:Inter,system-ui,-apple-system,sans-serif}' +
        '.global-toast{pointer-events:auto;display:flex;align-items:flex-start;gap:.75rem;width:100%;padding:1rem;border-radius:1rem;background:#fff;color:#1e293b;font-size:.85rem;font-weight:600;line-height:1.4;box-shadow:0 20px 40px -12px rgba(15,23,42,.25);transform:translateX(120%);opacity:0;transition:transform .35s ease,opacity .35s ease;word-break:break-word}' +
        '.global-toast.global-toast-error{background:#fff1f2;color:#9f1239;border:1px solid #fecdd3}' +
        '.global-toast.global-toast-success{background:#f0fdfa;color:#134e4a;border:1px solid #99f6e4}' +
        '.global-toast.global-toast-warning{background:#fffbeb;color:#92400e;border:1px solid #fde68a}' +
        '.global-toast i{font-size:1rem;flex-shrink:0;margin-top:.05em}' +
        '.global-toast .global-toast-msg{flex:1;min-width:0}' +
        '@media (prefers-color-scheme: dark){' +
        '.global-toast{background:#0f172a;color:#f1f5f9;border:1px solid rgba(148,163,184,.3)}' +
        '.global-toast.global-toast-error{background:#450a1a;color:#ffe4e6;border-color:rgba(244,63,94,.45)}' +
        '.global-toast.global-toast-success{background:#042f2e;color:#ccfbf1;border-color:rgba(45,212,191,.45)}' +
        '}';
    document.head.appendChild(style);

    function container() {
        var el = document.getElementById('globalToastContainer');
        if (!el) {
            el = document.createElement('div');
            el.id = 'globalToastContainer';
            el.setAttribute('aria-live', 'polite');
            document.body.appendChild(el);
        }
        return el;
    }

    function escapeHtml(str) {
        return String(str == null ? '' : str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    window.showToast = function (message, type) {
        var known = ['success', 'error', 'warning'];
        type = known.indexOf(type) !== -1 ? type : 'success';
        var wrap = container();

        while (wrap.children.length >= MAX_TOASTS) {
            wrap.removeChild(wrap.lastChild);
        }

        var el = document.createElement('div');
        el.className = 'global-toast global-toast-' + type;
        el.setAttribute('role', 'status');

        var icons = {
            success: 'fa-solid fa-circle-check',
            error: 'fa-solid fa-circle-exclamation',
            warning: 'fa-solid fa-triangle-exclamation'
        };
        el.innerHTML =
            '<i class="' + icons[type] + '"></i>' +
            '<span class="global-toast-msg">' + escapeHtml(message) + '</span>';

        wrap.appendChild(el);
        requestAnimationFrame(function () {
            el.style.transform = 'translateX(0)';
            el.style.opacity = '1';
        });

        setTimeout(function () {
            el.style.transform = 'translateX(120%)';
            el.style.opacity = '0';
            setTimeout(function () {
                if (el.parentNode) {
                    el.parentNode.removeChild(el);
                }
            }, FADE_MS);
        }, DURATION_MS);

        el.addEventListener('click', function () {
            el.style.transform = 'translateX(120%)';
            el.style.opacity = '0';
            setTimeout(function () {
                if (el.parentNode) {
                    el.parentNode.removeChild(el);
                }
            }, FADE_MS);
        });
    };
})();
