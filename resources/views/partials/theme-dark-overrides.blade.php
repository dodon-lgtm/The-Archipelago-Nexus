<style>
    /* =========================================================
       APEXFORGE LABS — DARK MODE OVERRIDES (ADMIN)
       Partial ini hanya di-include oleh layouts/admin.blade.php,
       sehingga selector html.dark di bawah ini aman (tidak
       memengaruhi halaman freelancer/company/landing).
       ========================================================= */
    html.dark {
        --af-page: #0f172a;
        --af-surface: #1e293b;
        --af-ink: #f8fafc;
        --af-muted: #94a3b8;
        --af-border: #334155;
        --af-primary-soft: #1e293b;
        color-scheme: dark;
    }

    /* ---------- body & page ---------- */
    html.dark body {
        background: #0f172a;
        color: #e2e8f0;
    }

    /* ---------- surface / cards ---------- */
    html.dark .bg-white { background: #1e293b !important; }
    html.dark .bg-slate-50 { background: #16233a !important; }
    html.dark .bg-slate-100 { background: #1e293b !important; }
    html.dark .bg-slate-200 { background: #334155 !important; }
    html.dark .bg-orange-50 { background: rgba(249, 115, 22, .14) !important; }
    html.dark .bg-indigo-50 { background: rgba(99, 102, 241, .14) !important; }
    html.dark .bg-\[\#f6f9ff\] { background: #0f172a !important; }
    html.dark .hover\:bg-\[\#f6f9ff\]\:hover { background: #16233a !important; }
    html.dark .hover\:bg-slate-100\:hover { background: #334155 !important; }
    html.dark .hover\:bg-slate-200\:hover { background: #334155 !important; }
    html.dark .bg-slate-50\/50 { background: rgba(15, 23, 42, .8) !important; }
    html.dark .bg-slate-50\/60 { background: rgba(15, 23, 42, .7) !important; }
    html.dark .hover\:bg-blue-50\/60\:hover { background: rgba(59, 130, 246, .16) !important; }

    /* ---------- borders & dividers ---------- */
    html.dark .border-blue-50,
    html.dark .border-blue-100,
    html.dark .border-slate-100,
    html.dark .border-slate-200,
    html.dark .border-slate-300,
    html.dark .border-red-100,
    html.dark .border-red-200,
    html.dark .border-emerald-100,
    html.dark .border-emerald-200,
    html.dark .border-amber-100,
    html.dark .border-amber-200,
    html.dark .border-violet-100,
    html.dark .border-purple-100,
    html.dark .border-orange-200,
    html.dark .border-indigo-100,
    html.dark .border-rose-100,
    html.dark .border-rose-200 { border-color: #334155 !important; }
    html.dark .divide-slate-100 > :not([hidden]) ~ :not([hidden]),
    html.dark .divide-slate-50 > :not([hidden]) ~ :not([hidden]),
    html.dark .divide-blue-50 > :not([hidden]) ~ :not([hidden]) { border-color: #26334d !important; }

    /* ---------- text ---------- */
    html.dark main .text-slate-900,
    html.dark main .text-slate-800 { color: #f1f5f9 !important; }
    html.dark main .text-slate-700 { color: #e2e8f0 !important; }
    html.dark main .text-slate-600 { color: #cbd5e1 !important; }
    html.dark main .text-slate-500 { color: #94a3b8 !important; }
    html.dark main .text-red-500,
    html.dark main .text-red-600,
    html.dark main .text-red-700 { color: #f87171 !important; }
    html.dark main .text-emerald-500,
    html.dark main .text-emerald-600,
    html.dark main .text-emerald-700 { color: #34d399 !important; }
    html.dark main .text-amber-500,
    html.dark main .text-amber-600,
    html.dark main .text-amber-700 { color: #fbbf24 !important; }
    html.dark main .text-blue-500,
    html.dark main .text-blue-600 { color: #60a5fa !important; }
    html.dark main .text-blue-700 { color: #93c5fd !important; }
    html.dark main .text-violet-500,
    html.dark main .text-violet-600 { color: #a78bfa !important; }
    html.dark main .text-purple-500,
    html.dark main .text-purple-600 { color: #c084fc !important; }
    html.dark main .text-indigo-600 { color: #818cf8 !important; }
    html.dark main .text-cyan-400,
    html.dark main .text-cyan-500,
    html.dark main .text-cyan-600 { color: #22d3ee !important; }
    html.dark main .text-orange-500,
    html.dark main .text-orange-600 { color: #fb923c !important; }
    html.dark main .text-rose-500,
    html.dark main .text-rose-600,
    html.dark main .text-rose-700 { color: #fb7185 !important; }

    /* ---------- soft backgrounds untuk badge/chip/tile ---------- */
    html.dark .bg-blue-50 { background: rgba(59, 130, 246, .14) !important; }
    html.dark .hover\:bg-blue-50\:hover { background: rgba(59, 130, 246, .26) !important; }
    html.dark .bg-blue-100 { background: rgba(59, 130, 246, .28) !important; }
    html.dark .hover\:bg-blue-100\:hover { background: rgba(59, 130, 246, .36) !important; }
    html.dark .bg-emerald-50 { background: rgba(16, 185, 129, .14) !important; }
    html.dark .hover\:bg-emerald-50\:hover,
    html.dark .hover\:bg-emerald-100\:hover { background: rgba(16, 185, 129, .26) !important; }
    html.dark .bg-emerald-100 { background: rgba(16, 185, 129, .22) !important; }
    html.dark .bg-red-50 { background: rgba(239, 68, 68, .14) !important; }
    html.dark .bg-red-100 { background: rgba(239, 68, 68, .24) !important; }
    html.dark .hover\:bg-red-50\:hover,
    html.dark .hover\:bg-red-100\:hover { background: rgba(239, 68, 68, .26) !important; }
    html.dark .bg-amber-50 { background: rgba(245, 158, 11, .14) !important; }
    html.dark .bg-amber-100 { background: rgba(245, 158, 11, .24) !important; }
    html.dark .bg-violet-50 { background: rgba(139, 92, 246, .14) !important; }
    html.dark .bg-violet-100 { background: rgba(139, 92, 246, .24) !important; }
    html.dark .bg-purple-50 { background: rgba(168, 85, 247, .14) !important; }
    html.dark .bg-purple-100 { background: rgba(168, 85, 247, .24) !important; }
    html.dark .bg-rose-50 { background: rgba(244, 63, 94, .14) !important; }
    html.dark .bg-rose-100 { background: rgba(244, 63, 94, .22) !important; }

    /* ---------- inputs ---------- */
    html.dark input:not([type="checkbox"]):not([type="radio"]):not([type="hidden"]):not([type="file"]),
    html.dark select,
    html.dark textarea {
        background: #0f172a !important;
        color: #f1f5f9 !important;
        border-color: #334155 !important;
    }
    html.dark input::placeholder,
    html.dark textarea::placeholder { color: #64748b !important; }

    /* ---------- tables ---------- */
    html.dark thead th { background: #1e293b !important; color: #cbd5e1 !important; }
    html.dark tbody tr:hover { background: rgba(30, 41, 59, .6) !important; }
    html.dark .divide-y > :not([hidden]) ~ :not([hidden]) { border-color: #26334d !important; }

    /* ---------- glass panels ---------- */
    html.dark .glass-panel,
    html.dark .glass-card,
    html.dark .glass-surface {
        background: rgba(15, 23, 42, 0.78);
        border-color: #334155;
    }

    /* ---------- sidebar ---------- */
    html.dark #sidebar { background: #0f172a !important; border-color: #334155 !important; }
    html.dark #sidebar .sidebar-logo-wrapper,
    html.dark #sidebar .sidebar-footer-wrapper { border-color: #334155 !important; }
    html.dark #sidebar a { color: #cbd5e1; }
    html.dark #sidebar a:hover { background: #1e293b !important; color: #f8fafc !important; }
    html.dark #sidebar .text-slate-800,
    html.dark #sidebar .text-slate-600 { color: #e2e8f0 !important; }
    html.dark #sidebar .bg-blue-50 { background: #1e293b !important; }
    html.dark #sidebar .border-blue-50,
    html.dark #sidebar .border-blue-100 { border-color: #334155 !important; }

    /* ---------- header/topbar ---------- */
    html.dark header { color: #e2e8f0; }

    /* ---------- shadow di dark (ringan) ---------- */
    html.dark .shadow-sm { box-shadow: 0 1px 2px 0 rgba(0, 0, 0, .4) !important; }

    /* =========================================================
       DASHBOARD (hologram / glass system)
       ========================================================= */
    /* Latar grid dashboard: versi gelap */
    html.dark .hologram-grid-bg {
        background-color: #0f172a !important;
        background-image:
            linear-gradient(to right, rgba(59, 130, 246, .06) 1px, transparent 1px),
            linear-gradient(to bottom, rgba(59, 130, 246, .06) 1px, transparent 1px) !important;
    }

    /* Orb dekoratif: multiply tidak bekerja di dark -> screen */
    html.dark .hologram-grid-bg .mix-blend-multiply { mix-blend-mode: screen; }

    /* Panel kaca terang -> mengikuti tema */
    html.dark .glass-panel-light {
        background: rgba(30, 41, 59, .82) !important;
        border: 1px solid #334155 !important;
        box-shadow: 0 18px 50px -32px rgba(0, 0, 0, .65) !important;
    }

    /* Elemen turunan dashboard */
    html.dark .bg-white\/50 { background: rgba(30, 41, 59, .55) !important; }
    html.dark .bg-cyan-50 { background: rgba(6, 182, 212, .14) !important; }
    html.dark .border-cyan-100 { border-color: #155e7566 !important; }
    html.dark main .text-cyan-700 { color: #67e8f9 !important; }
    html.dark main .text-cyan-800 { color: #a5f3fc !important; }
    html.dark main .hover\:text-cyan-800:hover { color: #a5f3fc !important; }
    html.dark main .hover\:text-blue-800:hover { color: #bfdbfe !important; }
    html.dark .hover\:bg-cyan-50\/40:hover { background: rgba(6, 182, 212, .12) !important; }
    html.dark .bg-blue-50\/60 { background: rgba(59, 130, 246, .14) !important; }
    html.dark .bg-red-50\/40 { background: rgba(239, 68, 68, .10) !important; }
    html.dark .hover\:bg-\[\#f6f9ff\]\/70:hover,
    html.dark .hover\:bg-\[\#f6f9ff\]\/50:hover { background: rgba(30, 41, 59, .6) !important; }
    html.dark .before\:bg-slate-200::before { background: #334155 !important; }
    html.dark .border-blue-50\/50,
    html.dark .border-blue-50\/80,
    html.dark .border-blue-100\/50 { border-color: #2c3a54 !important; }
    html.dark .bg-gradient-to-r.from-blue-50\/50,
    html.dark .bg-gradient-to-r.from-cyan-50\/60 { background-image: none !important; }

    /* Text blue-800 pada latar gelap tetap terbaca */
    html.dark main .text-blue-800 { color: #93c5fd !important; }
</style>
