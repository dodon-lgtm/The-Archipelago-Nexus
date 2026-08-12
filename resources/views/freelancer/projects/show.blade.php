<!DOCTYPE html>
<html lang="id" class="h-full bg-[#f6f9ff]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $project->project_name ?? 'Detail Proyek' }} - ApexForge Labs</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }

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
    </style>
</head>

<body class="h-full bg-[#f6f9ff] text-slate-800 antialiased selection:bg-blue-600 selection:text-white flex">

    @include('navbar.navigasi')

    <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">

        <div class="sticky top-0 z-40 bg-white/80 backdrop-blur-md border-b border-blue-100/80 shadow-xs">
            @include('navbar.nav')
        </div>

        <main class="flex-1 overflow-y-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="max-w-7xl mx-auto">

                <!-- Navigation Back -->
                <div class="mb-6">
                    <a href="{{ route('freelancer.dashboard') }}"
                       class="inline-flex items-center gap-2 text-xs font-bold text-blue-600 hover:text-blue-700 bg-blue-50 hover:bg-blue-100/80 px-3.5 py-2 rounded-xl transition border border-blue-100">
                        <i class="fa-solid fa-arrow-left text-[10px]"></i> Kembali ke Dashboard
                    </a>
                </div>

                {{-- Flash Notifications --}}
                @if(session('error'))
                    <div class="bg-rose-50/90 border border-rose-200 text-rose-800 px-5 py-4 rounded-2xl mb-6 flex items-center gap-3 shadow-xs">
                        <span class="w-8 h-8 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-circle-exclamation text-sm"></i>
                        </span>
                        <span class="text-sm font-semibold">{{ session('error') }}</span>
                    </div>
                @endif

                @if(session('success'))
                    <div class="bg-emerald-50/90 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-2xl mb-6 flex items-center gap-3 shadow-xs">
                        <span class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-circle-check text-sm"></i>
                        </span>
                        <span class="text-sm font-semibold">{{ session('success') }}</span>
                    </div>
                @endif

                @if(isset($project))
                    <div class="grid lg:grid-cols-3 gap-8">

                        <!-- LEFT SIDE: Project Main Details -->
                        <div class="lg:col-span-2 space-y-6">
                            <div class="bg-white rounded-3xl border border-blue-100/80 shadow-xs p-6 sm:p-8">

                                <!-- Cover Image -->
                                <div class="relative h-64 sm:h-80 w-full overflow-hidden rounded-2xl bg-blue-50 border border-blue-100/60">
                                    @if($project->image)
                                        <img src="{{ asset('storage/'.$project->image) }}"
                                             class="w-full h-full object-cover" alt="{{ $project->project_name }}">
                                    @else
                                        <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-slate-50 to-blue-50/60 text-slate-300">
                                            <i class="fa-solid fa-image text-5xl mb-2 text-slate-300"></i>
                                            <span class="text-xs font-semibold text-slate-400">Tidak ada gambar proyek</span>
                                        </div>
                                    @endif

                                    <!-- Category & Status Overlay Badges -->
                                    <div class="absolute top-4 left-4 flex flex-wrap gap-2">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-white/90 backdrop-blur-md text-blue-700 font-bold text-xs rounded-full shadow-xs border border-white/40">
                                            <i class="fa-solid fa-folder-open text-blue-500 text-[10px]"></i>
                                            {{ optional($project->category)->name ?? 'Umum' }}
                                        </span>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-500/90 backdrop-blur-md text-white font-bold text-xs rounded-full shadow-xs border border-emerald-400/30">
                                            <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                                            {{ ucfirst($project->status ?? 'Open') }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Title -->
                                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-6 tracking-tight leading-snug">
                                    {{ $project->project_name }}
                                </h1>

                                <!-- Description -->
                                <div class="mt-8 pt-6 border-t border-blue-50">
                                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Deskripsi Proyek</h3>
                                    <p class="text-slate-600 text-sm sm:text-base leading-relaxed whitespace-pre-line">{{ $project->project_description ?? 'Tidak ada deskripsi.' }}</p>
                                </div>

                                <!-- Required Skills -->
                                @if(!empty($project->skills))
                                    <div class="mt-8 pt-6 border-t border-blue-50">
                                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Keahlian Yang Dibutuhkan</h3>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach(explode(',', $project->skills) as $skill)
                                                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-blue-50/80 border border-blue-100 text-blue-700 text-xs font-semibold rounded-xl">
                                                    <i class="fa-solid fa-code text-[10px] text-blue-400"></i>
                                                    {{ trim($skill) }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Attachment Section -->
                                <div class="mt-8 pt-6 border-t border-blue-50">
                                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Lampiran Proyek</h3>
                                    @if($project->attachment)
                                        <div class="border border-blue-100/80 rounded-2xl p-4 flex items-center justify-between bg-[#f6f9ff]/60 hover:bg-[#f6f9ff] transition">
                                            <div class="flex items-center gap-3.5 min-w-0">
                                                <div class="w-11 h-11 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center shrink-0 font-bold">
                                                    <i class="fa-solid fa-file-pdf text-xl"></i>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="font-bold text-sm text-slate-800 truncate">{{ $project->attachment }}</p>
                                                    <p class="text-xs text-slate-400">Dokumen pendukung dari perusahaan</p>
                                                </div>
                                            </div>
                                            <a href="{{ asset('storage/'.$project->attachment) }}" target="_blank"
                                               class="shrink-0 inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold transition shadow-xs">
                                                <i class="fa-solid fa-download text-[10px]"></i> Unduh
                                            </a>
                                        </div>
                                    @else
                                        <div class="border border-dashed border-blue-100 rounded-2xl p-5 text-center text-slate-400 text-xs font-medium">
                                            <i class="fa-regular fa-folder-open text-base mb-1 block text-slate-300"></i>
                                            Tidak ada berkas lampiran untuk proyek ini.
                                        </div>
                                    @endif
                                </div>

                            </div>
                        </div>

                        <!-- RIGHT SIDE: Project Meta & Actions Sidebar -->
                        <div class="space-y-6">
                            <div class="bg-white rounded-3xl border border-blue-100/80 shadow-xs p-6 sticky top-24">
                                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-5">Ringkasan Informasi</h2>
                                
                                <div class="space-y-4 text-sm">
                                    <!-- Budget -->
                                    <div class="bg-blue-50/60 border border-blue-100/80 rounded-2xl p-4">
                                        <p class="text-xs text-blue-600/80 font-bold uppercase tracking-wider mb-1">Anggaran Proyek</p>
                                        <h3 class="text-2xl font-extrabold text-blue-600">Rp {{ number_format($project->budget ?? 0, 0, ',', '.') }}</h3>
                                    </div>

                                    <!-- Meta List -->
                                    <div class="space-y-3 pt-2">
                                        <div class="flex items-center justify-between py-2 border-b border-blue-50">
                                            <span class="text-slate-500 font-medium text-xs flex items-center gap-2">
                                                <i class="fa-regular fa-calendar text-slate-400"></i> Tenggat Waktu
                                            </span>
                                            <span class="font-bold text-slate-800 text-xs">
                                                {{ $project->deadline ? \Carbon\Carbon::parse($project->deadline)->format('d M Y') : '-' }}
                                            </span>
                                        </div>

                                        <div class="flex items-center justify-between py-2 border-b border-blue-50">
                                            <span class="text-slate-500 font-medium text-xs flex items-center gap-2">
                                                <i class="fa-solid fa-signal text-slate-400"></i> Status Proyek
                                            </span>
                                            <span class="font-bold text-emerald-600 text-xs bg-emerald-50 px-2.5 py-0.5 rounded-md border border-emerald-100">
                                                {{ ucfirst($project->status ?? 'Open') }}
                                            </span>
                                        </div>

                                        <div class="flex items-center justify-between py-2">
                                            <span class="text-slate-500 font-medium text-xs flex items-center gap-2">
                                                <i class="fa-regular fa-building text-slate-400"></i> Klien / Perusahaan
                                            </span>
                                            <span class="font-bold text-slate-800 text-xs truncate max-w-[140px]">
                                                {{ optional($project->owner)->name ?? 'Perusahaan' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions Group -->
                                <div class="mt-8 space-y-3 pt-4 border-t border-blue-50">
                                    @if(!empty($hasOffered))
                                        <a href="{{ route('freelancer.lamaran') }}" 
                                           class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-center py-3 rounded-2xl font-bold text-xs transition">
                                            <i class="fa-solid fa-list-check"></i> Lihat Penawaran Saya
                                        </a>
                                    @elseif(!empty($acceptsOffers))
                                        <a href="{{ route('freelancer.penawaran.create', $project)}}" 
                                           class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-center py-3 rounded-2xl font-bold text-xs transition">
                                            <i class="fa-solid fa-paper-plane"></i> Kirim Penawaran Baru
                                        </a>
                                    @else
                                        <div class="w-full inline-flex items-center justify-center gap-2 border border-blue-100 bg-[#f6f9ff] text-slate-500 text-center py-3 rounded-2xl font-bold text-xs">
                                            <i class="fa-solid fa-lock"></i> Proyek Sudah Ditutup
                                        </div>
                                    @endif

                                    @php
                                        $isSaved = false;
                                        if (method_exists($project, 'savedByFreelancers')) {
                                            $isSaved = $project->savedByFreelancers()->where('freelancer_id', auth()->id())->exists();
                                        }
                                    @endphp

                                    @if($isSaved)
                                        <form action="{{ route('freelancer.saved-projects.destroy', $project) }}" method="POST" class="w-full">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 border border-blue-200 bg-blue-50/80 text-blue-700 py-3 rounded-2xl hover:bg-blue-100 font-bold text-xs transition">
                                                <i class="fa-solid fa-bookmark text-blue-600"></i> Tersimpan Dalam Bookmark
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('freelancer.saved-projects.store', $project) }}" method="POST" class="w-full">
                                            @csrf
                                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 border border-blue-100/80 bg-white text-slate-700 py-3 rounded-2xl hover:bg-[#f6f9ff] font-semibold text-xs transition">
                                                <i class="fa-regular fa-bookmark"></i> Simpan Ke Bookmark
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Report Buttons --}}
                                    <a href="{{ route('freelancer.reports.create', ['project_id' => $project->id]) }}"
                                       class="w-full inline-flex items-center justify-center gap-2 border border-rose-200/80 bg-rose-50/50 text-rose-600 hover:bg-rose-100/80 text-center py-3 rounded-2xl font-semibold text-xs transition">
                                        <i class="fa-solid fa-flag"></i> Laporkan Masalah Proyek
                                    </a>

                                    @if($project->owner && (int) $project->owner->id !== (int) auth()->id())
                                        <a href="{{ route('freelancer.reports.create', ['reported_user_id' => $project->owner->id]) }}"
                                           class="w-full inline-flex items-center justify-center gap-2 border border-blue-100/80 bg-white text-slate-600 hover:bg-[#f6f9ff] text-center py-3 rounded-2xl font-semibold text-xs transition">
                                            <i class="fa-solid fa-building-shield"></i> Laporkan Perusahaan
                                        </a>
                                    @endif
                                </div>

                            </div>
                        </div>

                    </div>
                @else
                    <div class="bg-white rounded-3xl border border-blue-100 p-12 text-center my-8">
                        <i class="fa-solid fa-folder-open text-4xl text-slate-300 mb-3"></i>
                        <h3 class="text-base font-bold text-slate-700">Data Proyek Tidak Ditemukan</h3>
                        <p class="text-xs text-slate-400 mt-1">Proyek mungkin telah dihapus atau tidak tersedia.</p>
                    </div>
                @endif

                <div class="mt-16">
                    @include('navbar.footer')
                </div>

            </div>
        </main>
    </div>

</body>
</html>