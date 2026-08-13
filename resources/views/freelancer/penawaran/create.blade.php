    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Kirim Penawaran</title>

        <script>
            if (localStorage.getItem('theme') === 'dark') {
                document.documentElement.classList.add('dark');
            }
        </script>
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = tailwind.config || {};
            tailwind.config.darkMode = 'class';
        </script>

        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

        <style>
            @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

            body{
                font-family:'Plus Jakarta Sans',sans-serif;
            }

            /* High-Tech Scrollbar (Blue/White Theme) */
            ::-webkit-scrollbar { width: 6px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { background: rgba(59, 130, 246, 0.2); border-radius: 9999px; }
            ::-webkit-scrollbar-thumb:hover { background: rgba(59, 130, 246, 0.5); }

            /* Pure Blue Styling */
            .hologram-grid-blue {
                background-image: 
                    linear-gradient(to right, rgba(59, 130, 246, 0.04) 1px, transparent 1px),
                    linear-gradient(to bottom, rgba(59, 130, 246, 0.04) 1px, transparent 1px);
                background-size: 32px 32px;
            }

            .btn-shimmer { position: relative; overflow: hidden; isolation: isolate; }
            .btn-shimmer::after {
                content: ''; position: absolute; top: 0; left: -75%;
                width: 50%; height: 100%;
                background: linear-gradient(120deg, transparent, rgba(255,255,255,.4), transparent);
                transform: skewX(-20deg); transition: left .65s ease;
            }
            .btn-shimmer:hover::after { left: 125%; }

            .glass-card {
                background: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
                border: 1px solid rgba(59, 130, 246, 0.1);
                box-shadow: 0 20px 50px -10px rgba(30, 58, 138, 0.1);
            }
        </style>
    <style>

/* ApexForge Labs — Unified UI System */
:root{
    --af-primary:#2563eb;
    --af-primary-dark:#1d4ed8;
    --af-primary-soft:#eff6ff;
    --af-sky:#38bdf8;
    --af-ink:#0f172a;
    --af-muted:#64748b;
    --af-border:#dbeafe;
    --af-surface:#ffffff;
    --af-page:#f6f9ff;
}
html{scroll-behavior:smooth}
body{
    font-family:'Plus Jakarta Sans',sans-serif;
    background:
        radial-gradient(circle at 10% -10%,rgba(56,189,248,.10),transparent 30%),
        radial-gradient(circle at 100% 0%,rgba(37,99,235,.08),transparent 28%),
        var(--af-page);
}
::selection{background:rgba(37,99,235,.18);color:#0f172a}
::-webkit-scrollbar{width:7px;height:7px}
::-webkit-scrollbar-track{background:rgba(241,245,249,.7)}
::-webkit-scrollbar-thumb{background:rgba(37,99,235,.22);border-radius:999px}
::-webkit-scrollbar-thumb:hover{background:rgba(37,99,235,.38)}

input,select,textarea{
    border-color:var(--af-border)!important;
    background:rgba(255,255,255,.92);
    transition:border-color .2s ease,box-shadow .2s ease,background .2s ease;
}
input:focus,select:focus,textarea:focus{
    border-color:rgba(37,99,235,.55)!important;
    box-shadow:0 0 0 4px rgba(37,99,235,.09)!important;
    outline:none!important;
}
button,a,[role="button"]{transition:all .2s ease}
button:focus-visible,a:focus-visible,[role="button"]:focus-visible{
    outline:2px solid rgba(37,99,235,.55);
    outline-offset:2px;
}
table{border-collapse:separate;border-spacing:0}
thead th{
    background:rgba(239,246,255,.72)!important;
    color:#334155;
    font-weight:700;
}
tbody tr{transition:background .18s ease}
tbody tr:hover{background:rgba(239,246,255,.48)}
[class*="bg-blue-600"]{
    box-shadow:0 8px 22px -12px rgba(37,99,235,.72);
}
[class*="bg-blue-600"]:hover{
    box-shadow:0 12px 28px -12px rgba(37,99,235,.78);
    transform:translateY(-1px);
}
.glass-panel,.glass-card,.glass-surface{
    background:rgba(255,255,255,.72);
    border:1px solid rgba(219,234,254,.85);
    backdrop-filter:blur(18px);
    -webkit-backdrop-filter:blur(18px);
    box-shadow:0 18px 50px -32px rgba(30,64,175,.32);
}
.apex-page-glow{
    position:fixed;inset:auto -10rem -12rem auto;width:28rem;height:28rem;
    background:rgba(56,189,248,.09);filter:blur(70px);border-radius:999px;
    pointer-events:none;z-index:-1;
}
@media (max-width:767px){
    main{padding-left:1rem!important;padding-right:1rem!important}
    table{min-width:680px}
    .overflow-x-auto{-webkit-overflow-scrolling:touch}
}
@media (prefers-reduced-motion:reduce){
    *,*::before,*::after{animation-duration:.01ms!important;animation-iteration-count:1!important;transition-duration:.01ms!important;scroll-behavior:auto!important}
}

</style>
</head>

    <body class="bg-white dark:bg-slate-900 text-blue-950 dark:text-white relative min-h-screen antialiased transition-colors duration-300">

        {{-- Ambient Background Glows --}}
        <div class="fixed inset-0 pointer-events-none hologram-grid-blue z-0"></div>
        <div class="fixed top-[-20%] right-[-10%] w-[50rem] h-[50rem] bg-gradient-to-bl from-blue-100/40 to-transparent rounded-full blur-[100px] pointer-events-none z-0"></div>

        <div class="relative z-10">
            @include('navbar.nav')

            <div class="max-w-7xl mx-auto py-10 px-6">

                <a href="{{ route('freelancer.projects.show',$project->id) }}"
                class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-blue-500 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-400 transition-colors mb-8 group">
                    <i class="fa fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                    Kembali ke Detail
                </a>

                <div class="grid lg:grid-cols-3 gap-8">

                    <!-- FORM -->
                    <div class="lg:col-span-2">

                        <div class="glass-card dark:bg-slate-900 dark:border-slate-700 rounded-3xl p-8 relative overflow-hidden transition-colors duration-300">
                            
                            {{-- Decorative gradient line --}}
                            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-400 via-blue-600 to-blue-400"></div>

                            <h2 class="text-2xl font-black text-blue-950 dark:text-white tracking-tight mb-8">
                                Kirim Penawaran
                            </h2>

                            <form
                                action="{{ route('freelancer.penawaran.store',$project) }}"
                                method="POST"
                                enctype="multipart/form-data">

                                @csrf

                                <div class="mb-6 relative">

                                    <label class="block text-[10px] font-black text-blue-500 dark:text-blue-400 uppercase tracking-widest mb-2">
                                        Harga Penawaran
                                    </label>

                                    {{-- Hidden input to store the actual integer for backend --}}
                                    <input type="hidden" name="harga_penawaran" id="real_harga_penawaran">

                                    {{-- Visible input for the formatted "1.000" visual --}}
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-5 pointer-events-none">
                                            <span class="text-blue-400 dark:text-slate-400 font-bold text-sm">Rp</span>
                                        </div>
                                        <input
                                            type="text"
                                            id="display_harga_penawaran"
                                            class="w-full bg-blue-50/50 dark:bg-slate-800/50 border border-blue-100 dark:border-slate-700 rounded-xl pl-12 pr-4 py-3.5 text-sm font-bold text-blue-950 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 focus:bg-white dark:focus:bg-slate-800 transition-all dark:placeholder:text-slate-500"
                                            placeholder="Contoh : 4500000">
                                    </div>

                                </div>

                                <div class="mb-6">

                                    <label class="block text-[10px] font-black text-blue-500 dark:text-blue-400 uppercase tracking-widest mb-2">
                                        Estimasi Pengerjaan (Hari)
                                    </label>

                                    <input
                                        type="number"
                                        name="estimasi_hari"
                                        class="w-full bg-blue-50/50 dark:bg-slate-800/50 border border-blue-100 dark:border-slate-700 rounded-xl px-5 py-3.5 text-sm font-bold text-blue-950 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 focus:bg-white dark:focus:bg-slate-800 transition-all dark:placeholder:text-slate-500"
                                        placeholder="Misal : 14">

                                </div>

                                <div class="mb-6">

                                    <label class="block text-[10px] font-black text-blue-500 dark:text-blue-400 uppercase tracking-widest mb-2">
                                        Pesan Kepada Perusahaan
                                    </label>

                                    <textarea
                                        name="pesan"
                                        rows="7"
                                        class="w-full bg-blue-50/50 dark:bg-slate-800/50 border border-blue-100 dark:border-slate-700 rounded-xl px-5 py-4 text-sm font-medium text-blue-950 dark:text-white leading-relaxed focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 focus:bg-white dark:focus:bg-slate-800 transition-all resize-none dark:placeholder:text-slate-500"
                                        placeholder="Perkenalkan diri dan jelaskan mengapa Anda cocok mengerjakan proyek ini..."></textarea>

                                </div>

                                <div class="mb-8">

                                    <label class="block text-[10px] font-black text-blue-500 dark:text-blue-400 uppercase tracking-widest mb-2">
                                        Upload Proposal (PDF)
                                    </label>

                                    <div class="relative w-full border-2 border-dashed border-blue-200 dark:border-slate-700 rounded-xl bg-blue-50/30 dark:bg-slate-800/30 hover:bg-blue-50/80 dark:hover:bg-slate-800/80 hover:border-blue-400 dark:hover:border-slate-700 transition-colors duration-300">
                                        <input
                                            type="file"
                                            name="proposal"
                                            accept=".pdf"
                                            class="w-full px-5 py-4 text-sm text-blue-900 dark:text-white cursor-pointer file:cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-[10px] file:font-black file:uppercase file:tracking-wider file:bg-blue-600 file:text-white hover:file:bg-blue-700 file:transition-colors focus:outline-none">
                                    </div>

                                </div>

                                <button
                                    class="btn-shimmer w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-10 py-3.5 rounded-xl text-sm font-bold shadow-[0_5px_15px_rgba(37,99,235,0.3)] transition-all flex items-center justify-center gap-2">

                                    <i class="fa fa-paper-plane"></i>
                                    Kirim Penawaran

                                </button>

                            </form>

                        </div>

                    </div>

                    <!-- SIDEBAR -->
                    <div>

                        <div class="glass-card dark:bg-slate-900 dark:border-slate-800 rounded-3xl p-6 sticky top-24 border border-blue-100">

                            <div class="rounded-2xl overflow-hidden border border-blue-50 dark:border-slate-800 mb-5 relative group shadow-sm">
                                <div class="absolute inset-0 bg-blue-600/10 opacity-0 group-hover:opacity-100 transition-opacity z-10"></div>
                                <img
                                    src="{{ asset('storage/'.$project->image) }}"
                                    alt="{{ $project->project_name }}"
                                    class="h-48 w-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                            </div>

                            <h2 class="font-black text-lg text-blue-950 dark:text-white tracking-tight leading-tight">
                                {{ $project->project_name }}
                            </h2>

                            <div class="mt-6 space-y-4">

                                <div>
                                    <p class="text-[9px] font-black tracking-widest uppercase text-blue-400 dark:text-slate-400 mb-1">
                                        Budget
                                    </p>
                                    <h3 class="font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-700 to-blue-500 text-2xl tracking-tighter drop-shadow-[0_2px_10px_rgba(59,130,246,0.1)]">
                                        Rp {{ number_format($project->budget,0,',','.') }}
                                    </h3>
                                </div>

                                <div class="h-px w-full bg-blue-100/50 dark:bg-slate-700/50"></div>

                                <div>
                                    <p class="text-[9px] font-black tracking-widest uppercase text-blue-400 dark:text-slate-400 mb-1">
                                        Deadline
                                    </p>
                                    <h3 class="font-bold text-blue-900 dark:text-white text-sm">
                                        {{ $project->deadline }}
                                    </h3>
                                </div>

                                <div class="h-px w-full bg-blue-100/50 dark:bg-slate-700/50"></div>

                                <div>
                                    <p class="text-[9px] font-black tracking-widest uppercase text-blue-400 dark:text-slate-400 mb-1">
                                        Perusahaan
                                    </p>
                                    <h3 class="font-bold text-blue-900 dark:text-white text-sm">
                                        {{ $project->owner->name }}
                                    </h3>
                                </div>

                                <div class="h-px w-full bg-blue-100/50 dark:bg-slate-700/50"></div>

                                <div>
                                    <p class="text-[9px] font-black tracking-widest uppercase text-blue-400 dark:text-slate-400 mb-2">
                                        Status
                                    </p>
                                    <span class="bg-blue-50 dark:bg-slate-800 border border-blue-200 dark:border-slate-700 text-blue-600 dark:text-blue-400 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest shadow-sm">
                                        {{ $project->status }}
                                    </span>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </div>

        {{-- Script untuk auto-format input angka --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const displayInput = document.getElementById('display_harga_penawaran');
                const realInput = document.getElementById('real_harga_penawaran');

                displayInput.addEventListener('input', function(e) {
                    // Hapus semua karakter yang bukan angka
                    let rawValue = this.value.replace(/[^0-9]/g, '');
                    
                    // Simpan nilai asli ke hidden input untuk disubmit ke backend
                    realInput.value = rawValue;

                    // Jika ada isinya, format dengan titik
                    if (rawValue !== '') {
                        // Gunakan locale id-ID untuk format ribuan, lalu pastikan menggunakan titik
                        let formatted = parseInt(rawValue, 10).toLocaleString('id-ID');
                        this.value = formatted;
                    } else {
                        this.value = '';
                    }
                });
            });
        </script>
    </body>
    </html>