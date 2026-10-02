<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.theme-boot')
    <title>Kirim Penawaran</title>

    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = tailwind.config || {};
    tailwind.config.darkMode = 'class';
        tailwind.config.darkMode = 'class';
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

        /* ApexForge Labs — Unified UI System Variables */
        :root {
            --af-primary: #2563eb;
            --af-primary-dark: #1d4ed8;
            --af-primary-soft: #eff6ff;
            --af-sky: #38bdf8;
            --af-ink: #0f172a;
            --af-muted: #64748b;
            --af-border: #dbeafe;
            --af-surface: #ffffff;
            --af-page: #f6f9ff;
        }

        .dark {
            --af-border: #334155;
            --af-surface: #0f172a;
            --af-page: #020617;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* High-Tech Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 7px;
            height: 7px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(59, 130, 246, 0.25);
            border-radius: 9999px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(59, 130, 246, 0.45);
        }

        .dark ::-webkit-scrollbar-thumb {
            background: rgba(51, 65, 85, 0.6);
        }

        .dark ::-webkit-scrollbar-thumb:hover {
            background: rgba(59, 130, 246, 0.5);
        }

        /* Pure Blue Hologram Grid */
        .hologram-grid-blue {
            background-image:
                linear-gradient(to right, rgba(59, 130, 246, 0.04) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(59, 130, 246, 0.04) 1px, transparent 1px);
            background-size: 32px 32px;
        }

        .dark .hologram-grid-blue {
            background-image:
                linear-gradient(to right, rgba(59, 130, 246, 0.07) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(59, 130, 246, 0.07) 1px, transparent 1px);
        }

        /* Shimmer Button Effect */
        .btn-shimmer {
            position: relative;
            overflow: hidden;
            isolation: isolate;
        }

        .btn-shimmer::after {
            content: '';
            position: absolute;
            top: 0;
            left: -75%;
            width: 50%;
            height: 100%;
            background: linear-gradient(120deg, transparent, rgba(255, 255, 255, .3), transparent);
            transform: skewX(-20deg);
            transition: left .65s ease;
        }

        .btn-shimmer:hover::after {
            left: 125%;
        }

        /* Glassmorphic Card */
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(219, 234, 254, 0.8);
            box-shadow: 0 20px 50px -10px rgba(30, 58, 138, 0.08);
        }

        .dark .glass-card {
            background: rgba(15, 23, 42, 0.75);
            border-color: rgba(51, 65, 85, 0.6);
            box-shadow: 0 20px 50px -10px rgba(0, 0, 0, 0.5);
        }

        /* Custom Input Dynamic Styling */
        input,
        select,
        textarea {
            transition: border-color .2s ease, box-shadow .2s ease, background-color .2s ease;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none !important;
        }
    </style>
</head>

<body
    class="bg-[#f6f9ff] dark:bg-[#030712] text-slate-800 dark:text-slate-100 relative min-h-screen antialiased transition-colors duration-300">

    {{-- Ambient Background Glows --}}
    <div class="fixed inset-0 pointer-events-none hologram-grid-blue z-0"></div>
    <div
        class="fixed top-[-20%] right-[-10%] w-[50rem] h-[50rem] bg-gradient-to-bl from-blue-400/10 dark:from-blue-600/15 to-transparent rounded-full blur-[120px] pointer-events-none z-0">
    </div>
    <div
        class="fixed bottom-[-10%] left-[-10%] w-[40rem] h-[40rem] bg-gradient-to-tr from-sky-400/10 dark:from-sky-500/10 to-transparent rounded-full blur-[100px] pointer-events-none z-0">
    </div>

    <div class="relative z-10">
        @include('navbar.nav')

        <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6">

            <!-- Back Link -->
            <a href="{{ route('freelancer.projects.show', $project->id) }}"
                class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors mb-8 group">
                <i class="fa fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                Kembali ke Detail
            </a>

            <div class="grid lg:grid-cols-3 gap-8">

                <!-- FORM SECTION -->
                <div class="lg:col-span-2">
                    <div class="glass-card rounded-3xl p-6 sm:p-8 relative overflow-hidden">

                        {{-- Top Decorative Line --}}
                        <div
                            class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 via-sky-400 to-blue-600">
                        </div>

                        <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight mb-8">
                            Kirim Penawaran
                        </h2>

                        <form action="{{ route('freelancer.penawaran.store', $project) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <!-- Harga Penawaran -->
                            <div class="mb-6">
                                <label
                                    class="block text-[10px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest mb-2">
                                    Harga Penawaran
                                </label>

                                <input type="hidden" name="harga_penawaran" id="real_harga_penawaran">

                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-5 pointer-events-none">
                                        <span class="text-blue-500 dark:text-slate-400 font-bold text-sm">Rp</span>
                                    </div>
                                    <input type="text" id="display_harga_penawaran"
                                        class="w-full bg-blue-50/50 dark:bg-slate-950/60 border border-blue-100 dark:border-slate-800 rounded-xl pl-12 pr-4 py-3.5 text-sm font-bold text-slate-900 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-500 focus:bg-white dark:focus:bg-slate-900 focus:border-blue-500 dark:focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:focus:ring-blue-500/20"
                                        placeholder="Contoh : 4.500.000" required>
                                </div>
                            </div>

                            <!-- Estimasi Pengerjaan -->
                            <div class="mb-6">
                                <label
                                    class="block text-[10px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest mb-2">
                                    Estimasi Pengerjaan (Hari)
                                </label>
                                <input type="number" name="estimasi_hari"
                                    class="w-full bg-blue-50/50 dark:bg-slate-950/60 border border-blue-100 dark:border-slate-800 rounded-xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-500 focus:bg-white dark:focus:bg-slate-900 focus:border-blue-500 dark:focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:focus:ring-blue-500/20"
                                    placeholder="Misal : 14" required>
                            </div>

                            <!-- Pesan -->
                            <div class="mb-6">
                                <label
                                    class="block text-[10px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest mb-2">
                                    Pesan Kepada Perusahaan
                                </label>
                                <textarea name="pesan" rows="6"
                                    class="w-full bg-blue-50/50 dark:bg-slate-950/60 border border-blue-100 dark:border-slate-800 rounded-xl px-5 py-4 text-sm font-medium text-slate-900 dark:text-slate-100 leading-relaxed placeholder:text-slate-400 dark:placeholder:text-slate-500 focus:bg-white dark:focus:bg-slate-900 focus:border-blue-500 dark:focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:focus:ring-blue-500/20 resize-none"
                                    placeholder="Perkenalkan diri dan jelaskan mengapa Anda cocok mengerjakan proyek ini..." required></textarea>
                            </div>

                            <!-- Upload Proposal -->
                            <div class="mb-8">
                                <label
                                    class="block text-[10px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest mb-2">
                                    Upload Proposal (PDF)
                                </label>

                                <div
                                    class="relative w-full border-2 border-dashed border-blue-200 dark:border-slate-800 rounded-2xl bg-blue-50/30 dark:bg-slate-950/40 hover:bg-blue-50/70 dark:hover:bg-slate-900/60 hover:border-blue-400 dark:hover:border-slate-700 transition-all duration-300">
                                    <input type="file" name="proposal" accept=".pdf"
                                        class="w-full px-5 py-4 text-sm text-slate-700 dark:text-slate-300 cursor-pointer 
                                                file:cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 
                                                file:text-[10px] file:font-black file:uppercase file:tracking-wider 
                                                file:bg-blue-600 dark:file:bg-blue-600 file:text-white hover:file:bg-blue-700 
                                                file:transition-colors focus:outline-none">
                                </div>
                            </div>

                            <!-- Button Submit -->
                            <button type="submit"
                                class="btn-shimmer w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-10 py-3.5 rounded-xl text-sm font-bold shadow-[0_8px_25px_-5px_rgba(37,99,235,0.4)] dark:shadow-[0_8px_25px_-5px_rgba(37,99,235,0.25)] transition-all flex items-center justify-center gap-2">
                                <i class="fa fa-paper-plane"></i>
                                Kirim Penawaran
                            </button>

                        </form>
                    </div>
                </div>

                <!-- SIDEBAR SECTION -->
                <div>
                    <div class="glass-card rounded-3xl p-6 sticky top-24">
                        <!-- Image Preview -->
                        <div
                            class="rounded-2xl overflow-hidden border border-blue-100 dark:border-slate-800 mb-5 relative group shadow-sm">
                            <div
                                class="absolute inset-0 bg-blue-600/10 dark:bg-blue-500/20 opacity-0 group-hover:opacity-100 transition-opacity z-10 pointer-events-none">
                            </div>
                            <img src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->project_name }}"
                                class="h-48 w-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                        </div>

                        <!-- Project Name -->
                        <h2 class="font-black text-lg text-slate-900 dark:text-white tracking-tight leading-snug">
                            {{ $project->project_name }}
                        </h2>

                        <div class="mt-6 space-y-4">

                            <!-- Budget -->
                            <div>
                                <p class="text-[9px] font-black tracking-widest uppercase text-blue-500 dark:text-slate-400 mb-1">
                                    Budget
                                </p>
                                <h3
                                    class="font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-sky-500 dark:from-blue-400 dark:to-sky-300 text-2xl tracking-tighter">
                                    Rp {{ number_format($project->budget, 0, ',', '.') }}
                                </h3>
                            </div>

                            <div class="h-px w-full bg-blue-100/60 dark:bg-slate-800"></div>

                            <!-- Deadline -->
                            <div>
                                <p class="text-[9px] font-black tracking-widest uppercase text-blue-500 dark:text-slate-400 mb-1">
                                    Deadline
                                </p>
                                <h3 class="font-bold text-slate-800 dark:text-slate-200 text-sm">
                                    {{ $project->deadline }}
                                </h3>
                            </div>

                            <div class="h-px w-full bg-blue-100/60 dark:bg-slate-800"></div>

                            <!-- Owner -->
                            <div>
                                <p class="text-[9px] font-black tracking-widest uppercase text-blue-500 dark:text-slate-400 mb-1">
                                    Perusahaan
                                </p>
                                <h3 class="font-bold text-slate-800 dark:text-slate-200 text-sm">
                                    {{ $project->owner->name }}
                                </h3>
                            </div>

                            <div class="h-px w-full bg-blue-100/60 dark:bg-slate-800"></div>

                            <!-- Status -->
                            <div>
                                <p class="text-[9px] font-black tracking-widest uppercase text-blue-500 dark:text-slate-400 mb-2">
                                    Status
                                </p>
                                <span
                                    class="inline-block bg-blue-50 dark:bg-blue-950/60 border border-blue-200 dark:border-blue-800/80 text-blue-600 dark:text-blue-300 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest shadow-sm">
                                    {{ \App\Models\Project::statusLabel($project->status ?? 'open') }}
                                </span>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Script Formatting --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const displayInput = document.getElementById('display_harga_penawaran');
            const realInput = document.getElementById('real_harga_penawaran');

            if (displayInput && realInput) {
                displayInput.addEventListener('input', function() {
                    let rawValue = this.value.replace(/[^0-9]/g, '');
                    realInput.value = rawValue;

                    if (rawValue !== '') {
                        this.value = parseInt(rawValue, 10).toLocaleString('id-ID');
                    } else {
                        this.value = '';
                    }
                });
            }
        });
    </script>
</body>

</html>