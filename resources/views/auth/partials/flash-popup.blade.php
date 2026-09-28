{{-- ============================================================
    ApexForge Labs — Flash Popup (success / error)
    LOCAL komponenta auth — bukan sistem global project.
    Replace alert()/confirm() inline bar dengan popup modern.
    Responsive + dark mode + animasi masuk/keluar ringan.
    Argomento (optional):
        type    : 'success' | 'error'
        title   : judul singkat
        message : pesan utama
    ============================================================ --}}

@php
    $typeUi = [
        'success' => ['icon' => 'fa-solid fa-circle-check', 'icong' => 'from-emerald-500 to-teal-600 shadow-emerald-900/30', 'border' => 'border-emerald-200/50 dark:border-emerald-800/60', 'accent' => 'text-emerald-600 dark:text-emerald-400', 'msg' => 'text-emerald-700 dark:text-emerald-200', 'btngrad' => 'from-emerald-500 to-teal-600 shadow-emerald-500/30'],
        'error'   => ['icon' => 'fa-solid fa-circle-xmark',  'icong' => 'from-rose-500 to-red-600 shadow-rose-900/30',     'border' => 'border-rose-200/60 dark:border-rose-800/60',    'accent' => 'text-rose-600 dark:text-rose-400',    'msg' => 'text-rose-700 dark:text-rose-200',    'btngrad' => 'from-rose-500 to-red-600 shadow-rose-500/30'],
        'warning' => ['icon' => 'fa-solid fa-triangle-exclamation', 'icong' => 'from-amber-500 to-orange-600 shadow-amber-900/30', 'border' => 'border-amber-200/60 dark:border-amber-800/60', 'accent' => 'text-amber-600 dark:text-amber-400', 'msg' => 'text-amber-700 dark:text-amber-200', 'btngrad' => 'from-amber-500 to-orange-600 shadow-amber-500/30'],
        'info'    => ['icon' => 'fa-solid fa-circle-info',  'icong' => 'from-sky-500 to-blue-600 shadow-sky-900/30',        'border' => 'border-sky-200/60 dark:border-sky-800/60',       'accent' => 'text-sky-600 dark:text-sky-400',       'msg' => 'text-sky-700 dark:text-sky-200',       'btngrad' => 'from-sky-500 to-blue-600 shadow-sky-500/30'],
    ];
    $flashType  = in_array(($type ?? 'success'), array_keys($typeUi)) ? $type : 'success';
    $flashTitle = $title ?? '';
    $u          = $typeUi[$flashType];
@endphp

@if (!empty($message) || !empty($flashTitle))
<div id="afFlashPopupWrap"
     data-type="{{ $flashType }}"
     role="alert" aria-live="assertive"
     class="hidden fixed top-4 right-4 z-[80] w-[min(94vw,360px)]">

    <div id="afFlashCard"
         class="opacity-0 translate-y-2 scale-95 transition-all duration-300 ease-out
                overflow-hidden rounded-2xl border
                {{ $u['border'] }}
                bg-white/90 dark:bg-slate-900/90 backdrop-blur-xl
                shadow-2xl shadow-slate-900/15">

        <div class="flex items-start gap-3 px-4 py-4 sm:px-5">
            <span class="shrink-0 grid h-10 w-10 place-items-center rounded-full text-white shadow-lg bg-gradient-to-br {{ $u['icong'] }}">
                <i class="{{ $u['icon'] }} text-base"></i>
            </span>

            <div class="min-w-0 flex-1">
                @if (!empty($flashTitle))
                    <p class="text-xs font-extrabold tracking-wide uppercase {{ $u['accent'] }}">{{ $flashTitle }}</p>
                @endif
                <p class="{{ empty($flashTitle) ? 'text-base' : 'mt-1 text-sm' }} font-semibold {{ $u['msg'] }} leading-snug break-words">{{ $message }}</p>
            </div>

            <button type="button" data-flash-close aria-label="Tutup"
                class="shrink-0 text-slate-400 hover:text-slate-600 dark:text-slate-500 dark:hover:text-white transition-colors text-sm">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="px-4 py-3">
            <button type="button" data-flash-close
                class="w-full py-2.5 rounded-xl text-xs font-bold text-white
                       hover:brightness-110 active:scale-[0.98] transition
                       bg-gradient-to-r {{ $u['btngrad'] }}">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    (function () {
        var wrap = document.getElementById('afFlashPopupWrap');
        var card = document.getElementById('afFlashCard');
        if (!wrap || !card) return;

        var opened = false;
        function openPopup() {
            if (opened) return;
            opened = true;
            wrap.classList.remove('hidden');
            setTimeout(function () {
                card.classList.remove('opacity-0', 'translate-y-2', 'scale-95');
                card.classList.add('opacity-100', 'translate-y-0', 'scale-100');
            }, 60);
        }
        function closePopup() {
            card.classList.add('opacity-0', 'translate-y-2', 'scale-95');
            setTimeout(function () { wrap.classList.add('hidden'); }, 280);
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', openPopup, { once: true });
        } else {
            openPopup();
        }

        // Auto-tutup success/about (error/warning tetap tampak hingga Tutup klik)
        if (wrap.getAttribute('data-type') === 'success' || wrap.getAttribute('data-type') === 'info') {
            setTimeout(closePopup, 6000);
        }

        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closePopup(); });
        Array.prototype.forEach.call(document.querySelectorAll('[data-flash-close]'), function (btn) {
            btn.addEventListener('click', closePopup, { once: true });
        });
    })();
</script>
@endif