/**
 * Live Filter Admin — pencarian & filter otomatis tanpa reload halaman penuh.
 *
 * Kontrak dengan view (Blade):
 *   1. <form ... data-live-filter>            → form GET filter utama
 *   2. <div data-live-filter-results>         → kontainer hasil (bisa lebih dari satu,
 *                                               diganti berdasarkan urutan dokumen)
 *   3. <a ... data-live-filter-reset>         → link reset filter (opsional)
 *
 * Perilaku: teks → debounce 400ms; select/date → change; pagination fetch;
 * reset kosongkan field; URL disinkronkan; fetch gagal → navigasi normal.
 *
 * Halaman: users, categories, projects, penawarans, hasil-pekerjaan, reports,
 * company-account-requests, payments, withdrawals, wallet.
 *
 * Catatan: file terpisah dari freelancer/company live-filter agar penyesuaian
 * Admin tidak memengaruhi role lain.
 */
(function () {
    'use strict';

    var DEBOUNCE_MS = 400;
    var UPDATED_EVENT = 'live-filter:updated';
    var RESET_EVENT = 'live-filter:reset';

    function init() {
        var form = document.querySelector('form[data-live-filter]');
        if (!form) return;

        var resultsList = Array.prototype.slice.call(
            document.querySelectorAll('[data-live-filter-results]')
        );
        if (!resultsList.length) return;

        var debounceTimer = null;
        var requestSeq = 0;
        var activeController = null;
        var suppressUpdate = false;

        injectStyles();
        var pill = buildLoadingPill();

        // ------------------------------------------------state helpers
        function hasActiveFilter() {
            var els = form.elements;
            for (var i = 0; i < els.length; i++) {
                var el = els[i];
                if (!el.name || el.disabled) continue;
                if (el.type === 'hidden' || el.type === 'submit' || el.type === 'button') continue;
                if (String(el.value || '').trim() !== '') return true;
            }
            return false;
        }

        function syncResetLinks() {
            var active = hasActiveFilter();
            var links = document.querySelectorAll('[data-live-filter-reset]');
            for (var i = 0; i < links.length; i++) {
                links[i].classList.toggle('hidden', !active);
            }
        }

        function buildUrl() {
            var params = new URLSearchParams();
            var els = form.elements;
            for (var i = 0; i < els.length; i++) {
                var el = els[i];
                if (!el.name || el.disabled) continue;
                if (el.type === 'hidden' || el.type === 'submit' || el.type === 'button') continue;
                if ((el.type === 'checkbox' || el.type === 'radio') && !el.checked) continue;
                var value = String(el.value || '').trim();
                if (value === '') continue;
                params.set(el.name, value);
            }
            var qs = params.toString();
            return window.location.pathname + (qs ? '?' + qs : '');
        }

        // ------------------------------------------------loading state
        function setLoading(on) {
            for (var i = 0; i < resultsList.length; i++) {
                resultsList[i].classList.toggle('live-filter-busy', on);
                resultsList[i].setAttribute('aria-busy', on ? 'true' : 'false');
            }
            if (pill) pill.classList.toggle('show', on);
        }

        // ------------------------------------------------apply (fetch)
        function apply(url) {
            if (debounceTimer) {
                clearTimeout(debounceTimer);
                debounceTimer = null;
            }
            var seq = ++requestSeq;
            if (activeController) {
                activeController.abort();
                activeController = null;
            }
            if (typeof AbortController !== 'undefined') {
                activeController = new AbortController();
            }
            setLoading(true);

            fetch(url, {
                method: 'GET',
                credentials: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    Accept: 'text/html'
                },
                signal: activeController ? activeController.signal : undefined
            })
                .then(function (res) {
                    if (!res.ok) throw new Error('HTTP ' + res.status);
                    return res.text();
                })
                .then(function (html) {
                    if (seq !== requestSeq) return; // request lama — abaikan
                    var doc = new DOMParser().parseFromString(html, 'text/html');
                    var next = Array.prototype.slice.call(
                        doc.querySelectorAll('[data-live-filter-results]')
                    );
                    if (!next.length || next.length !== resultsList.length) {
                        window.location.href = url; // struktur tak cocok → navigasi penuh
                        return;
                    }
                    for (var i = 0; i < resultsList.length; i++) {
                        resultsList[i].innerHTML = next[i].innerHTML;
                    }
                    try {
                        window.history.replaceState(null, '', url);
                    } catch (err) { /* browser lama — abaikan */ }
                    setLoading(false);
                    syncResetLinks();
                })
                .catch(function (err) {
                    if (err && err.name === 'AbortError') return;
                    if (seq !== requestSeq) return;
                    setLoading(false);
                    window.location.href = url; // fetch gagal → navigasi normal
                });
        }

        function applyNow() {
            if (debounceTimer) {
                clearTimeout(debounceTimer);
                debounceTimer = null;
            }
            apply(buildUrl());
        }

        function scheduleApply() {
            if (debounceTimer) clearTimeout(debounceTimer);
            debounceTimer = setTimeout(function () {
                debounceTimer = null;
                apply(buildUrl());
            }, DEBOUNCE_MS);
        }

        function resetFilters() {
            suppressUpdate = true;
            var els = form.elements;
            for (var i = 0; i < els.length; i++) {
                var el = els[i];
                if (!el.name || el.disabled) continue;
                if (el.type === 'submit' || el.type === 'button') continue;
                if (el.tagName === 'SELECT') {
                    el.selectedIndex = 0;
                } else if (el.type !== 'checkbox' && el.type !== 'radio') {
                    el.value = '';
                }
            }
            // Biarkan komponen (mis. tombol hapus pencarian) menyelaraskan UI-nya.
            document.dispatchEvent(new CustomEvent(RESET_EVENT));
            suppressUpdate = false;
            applyNow();
        }

        // ------------------------------------------------event: input teks
        var textInputs = form.querySelectorAll('input[type="text"][name], input[type="search"][name], textarea[name]');
        for (var i = 0; i < textInputs.length; i++) {
            textInputs[i].addEventListener('input', scheduleApply);
            textInputs[i].addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    applyNow();
                }
            });
        }

        // ------------------------------------------------event: select / date / month / number
        var otherInputs = form.querySelectorAll('select[name], input[type="month"][name], input[type="date"][name], input[type="number"][name]');
        for (var j = 0; j < otherInputs.length; j++) {
            otherInputs[j].addEventListener('change', applyNow);
        }

        // ------------------------------------------------event: submit (Enter) tanpa reload penuh
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            applyNow();
        });

        // ------------------------------------------------event: dropdown custom (input hidden)
        document.addEventListener(UPDATED_EVENT, function () {
            if (suppressUpdate) return;
            applyNow();
        });

        // ------------------------------------------------event: link reset
        document.addEventListener('click', function (e) {
            var target = e.target;
            if (!target || !target.closest) return;
            var link = target.closest('[data-live-filter-reset]');
            if (!link) return;
            e.preventDefault();
            resetFilters();
        });

        // ------------------------------------------------event: pagination (delegated per kontainer)
        function bindPagination(container) {
            container.addEventListener('click', function (e) {
                if (e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
                var target = e.target;
                if (!target || !target.closest) return;
                var a = target.closest('a[href]');
                if (!a || !container.contains(a)) return;
                if (a.hasAttribute('data-live-filter-reset')) return;
                if (a.target && a.target !== '_self') return;
                var url;
                try {
                    url = new URL(a.href, window.location.href);
                } catch (err) {
                    return;
                }
                if (url.origin !== window.location.origin) return;
                if (url.pathname !== window.location.pathname) return;
                if (!url.searchParams.has('page')) return; // link lain → biarkan
                e.preventDefault();
                apply(url.pathname + url.search);
            });
        }
        for (var k = 0; k < resultsList.length; k++) {
            bindPagination(resultsList[k]);
        }

        syncResetLinks();
    }

    // ------------------------------------------------helper: styles & pill
    function injectStyles() {
        if (document.getElementById('admin-live-filter-style')) return;
        var style = document.createElement('style');
        style.id = 'admin-live-filter-style';
        style.textContent =
            '[data-live-filter-results]{transition:opacity .18s ease}' +
            '[data-live-filter-results].live-filter-busy{opacity:.55;pointer-events:none}' +
            '.live-filter-pill{position:fixed;top:5.5rem;left:50%;transform:translateX(-50%);z-index:70;' +
            'display:none;align-items:center;gap:.5rem;padding:.45rem .9rem;border-radius:9999px;' +
            'background:rgba(15,23,42,.92);color:#fff;font-size:.72rem;font-weight:700;letter-spacing:.02em;' +
            'box-shadow:0 10px 30px -10px rgba(15,23,42,.5);pointer-events:none}' +
            '.live-filter-pill.show{display:inline-flex}' +
            '.live-filter-spinner{width:.72rem;height:.72rem;border-radius:50%;' +
            'border:2px solid rgba(255,255,255,.35);border-top-color:#fff;' +
            'animation:liveFilterSpin .7s linear infinite}' +
            '@keyframes liveFilterSpin{to{transform:rotate(360deg)}}';
        document.head.appendChild(style);
    }

    function buildLoadingPill() {
        var pill = document.createElement('div');
        pill.className = 'live-filter-pill';
        pill.setAttribute('aria-hidden', 'true');
        var spinner = document.createElement('span');
        spinner.className = 'live-filter-spinner';
        var label = document.createElement('span');
        label.textContent = 'Memuat...';
        pill.appendChild(spinner);
        pill.appendChild(label);
        document.body.appendChild(pill);
        return pill;
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
