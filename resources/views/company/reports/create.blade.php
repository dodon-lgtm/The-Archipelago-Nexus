<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Laporan - The Archipelago Nexus</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif']
                    },
                    colors: {
                        brand: {
                            DEFAULT: '#2563EB',
                            dark: '#1D4ED8',
                            light: '#EFF6FF',
                        },
                        surface: '#F8FAFC'
                    }
                }
            }
        }
    </script>
    <style>
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-surface text-slate-800 min-h-screen flex font-sans antialiased">

    {{-- SIDEBAR --}}
    @include('navbar.navigasi')

    {{-- MAIN CONTENT --}}
    <div class="flex-1 flex flex-col min-h-screen w-full overflow-hidden">
        @include('navbar.nav')

        <main class="flex-1 w-full overflow-y-auto p-4 sm:p-6 lg:p-8">
            <div class="max-w-2xl mx-auto">

{{-- Flash Messages --}}
                @if(session('success'))
                    <div class="mb-4 flex items-center gap-3 px-5 py-4 bg-emerald-50/80 backdrop-blur-md border border-emerald-200/60 text-emerald-800 text-sm font-medium rounded-2xl shadow-sm">
                        <i class="fa-regular fa-circle-check"></i> {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-4 flex items-center gap-3 px-5 py-4 bg-red-50/80 backdrop-blur-md border border-red-200/60 text-red-800 text-sm font-medium rounded-2xl shadow-sm">
                        <i class="fa-regular fa-circle-xmark"></i> {{ session('error') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="mb-4 flex items-start gap-3 px-5 py-4 bg-red-50/80 backdrop-blur-md border border-red-200/60 text-red-800 text-sm font-medium rounded-2xl shadow-sm">
                        <i class="fa-regular fa-circle-xmark mt-0.5"></i>
                        <div>
                            @foreach($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Back Button --}}
                <div class="mb-4">
                    <a href="{{ route('company.reports.index') }}" class="text-sm text-brand hover:text-brand-dark font-semibold inline-flex items-center gap-1">
                        <i class="fa-solid fa-arrow-left"></i> Kembali ke Laporan
                    </a>
                </div>

                {{-- Form Card --}}
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-xl bg-red-50 text-red-500 flex items-center justify-center text-xl">
                            <i class="fa-solid fa-flag"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-slate-800">Buat Laporan</h2>
                            <p class="text-sm text-slate-500">Laporkan masalah, pengguna, atau proyek yang melanggar aturan</p>
                        </div>
                    </div>

<form method="POST" action="{{ route('company.reports.store') }}" enctype="multipart/form-data" class="space-y-5">
                        @csrf

{{-- Hidden inputs for contextual reporting --}}
                        @if($workspace)
                            <input type="hidden" name="workspace_id" value="{{ $workspace->id }}">
                            <input type="hidden" name="project_id" value="{{ $project ? $project->id : '' }}">
                            <input type="hidden" name="reported_user_id" value="{{ $reportedUser ? $reportedUser->id : '' }}">
                        @elseif($penawaran)
                            <input type="hidden" name="penawaran_id" value="{{ $penawaran->id }}">
                            <input type="hidden" name="project_id" value="{{ $project ? $project->id : '' }}">
                            <input type="hidden" name="reported_user_id" value="{{ $reportedUser ? $reportedUser->id : '' }}">
                        @endif

                        {{-- Context Info: Workspace (if reporting from workspace) --}}
                        @if($workspace)
                            <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 space-y-3">
                                <div class="flex items-center gap-2 text-slate-500 text-xs font-semibold uppercase tracking-wider">
                                    <i class="fa-solid fa-layer-group"></i>
                                    <span>Workspace yang Dilaporkan</span>
                                </div>
                                @if($reportedUser)
                                <div class="flex items-center gap-3 pb-3 border-b border-slate-200/60">
                                    <div class="w-10 h-10 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center font-bold">
                                        {{ strtoupper(substr($reportedUser->name ?? '?', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800">{{ $reportedUser->name }}</p>
                                        <p class="text-xs text-slate-500">Freelancer yang Dilaporkan</p>
                                    </div>
                                </div>
                                @endif
                                @if($project)
                                <div class="flex items-center gap-3 pt-1">
                                    <div class="w-10 h-10 rounded-lg bg-brand/10 text-brand flex items-center justify-center">
                                        <i class="fa-solid fa-briefcase"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800">{{ $project->project_name }}</p>
                                        <p class="text-xs text-slate-500">Proyek Terkait</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        @elseif($penawaran)
                            {{-- Context Info: Penawaran & Freelancer Details (if reporting from penawaran) --}}
                            <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 space-y-3">
                                <div class="flex items-center gap-2 text-slate-500 text-xs font-semibold uppercase tracking-wider">
                                    <i class="fa-solid fa-flag"></i>
                                    <span>Detail yang Dilaporkan</span>
                                </div>
                                @if($reportedUser)
                                <div class="flex items-center gap-3 pb-3 border-b border-slate-200/60">
                                    <div class="w-10 h-10 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center font-bold">
                                        {{ strtoupper(substr($reportedUser->name ?? '?', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800">{{ $reportedUser->name }}</p>
                                        <p class="text-xs text-slate-500">Freelancer yang Dilaporkan</p>
                                    </div>
                                </div>
                                @endif
                                @if($project)
                                <div class="flex items-center gap-3 pt-1">
                                    <div class="w-10 h-10 rounded-lg bg-brand/10 text-brand flex items-center justify-center">
                                        <i class="fa-solid fa-briefcase"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800">{{ $project->project_name }}</p>
                                        <p class="text-xs text-slate-500">Proyek Terkait</p>
                                    </div>
                                </div>
                                @endif
                                @if($penawaran->harga_penawaran)
                                <div class="text-xs text-slate-500 bg-white rounded-lg px-3 py-2">
                                    Penawaran: Rp {{ number_format($penawaran->harga_penawaran, 0, ',', '.') }} | Estimasi: {{ $penawaran->estimasi_hari }} hari
                                </div>
                                @endif
                            </div>
                        @endif

{{-- Category --}}
                        <div>
                            <label class="text-xs font-semibold text-slate-600 mb-1.5 block">Kategori Laporan <span class="text-red-500">*</span></label>
                            <select name="category"
                                class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:border-brand focus:ring-2 focus:ring-brand/10 outline-none @error('category') border-red-300 @enderror">
                                @foreach(\App\Models\Report::categories() as $cat)
                                    <option value="{{ $cat }}" @selected(old('category', 'umum') == $cat)>{{ \App\Models\Report::categoryLabel($cat) }}</option>
                                @endforeach
                            </select>
                            @error('category') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Subject --}}
                        <div>
                            <label class="text-xs font-semibold text-slate-600 mb-1.5 block">Subjek Laporan <span class="text-red-500">*</span></label>
                            <input type="text" name="subject" value="{{ old('subject') }}"
                                class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:border-brand focus:ring-2 focus:ring-brand/10 outline-none @error('subject') border-red-300 @enderror"
                                placeholder="Contoh: Freelancer tidak profesional, Proyek tidak sesuai, dll.">
                            @error('subject') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Description --}}
                        <div>
                            <label class="text-xs font-semibold text-slate-600 mb-1.5 block">Deskripsi <span class="text-red-500">*</span></label>
                            <textarea name="description" rows="5"
                                class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:border-brand focus:ring-2 focus:ring-brand/10 outline-none @error('description') border-red-300 @enderror"
                                placeholder="Jelaskan secara detail masalah yang Anda temui...">{{ old('description') }}</textarea>
                            @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

{{-- Attachment / Bukti --}}
                        <div>
                            <label class="text-xs font-semibold text-slate-600 mb-1.5 block">Lampiran / Bukti (opsional)</label>
                            <input type="file" name="attachments[]" multiple accept=".jpg,.jpeg,.png,.pdf"
                                   class="w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand/10 file:text-brand hover:file:bg-brand/20">
                            <p class="text-[11px] text-slate-400 mt-1">Maks 5 file. Format: JPG, JPEG, PNG, atau PDF. Maks 5 MB per file.</p>
                            @error('attachments') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            @error('attachments.*') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Info --}}
                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-sm text-amber-700 flex items-start gap-3">
                            <i class="fa-solid fa-shield-halved mt-0.5"></i>
                            <p>Laporan Anda akan ditinjau oleh tim admin. Pastikan Anda memberikan informasi yang benar dan jelas agar dapat diproses dengan baik.</p>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center gap-3 pt-2">
                            <button type="submit" class="px-6 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-xl text-sm font-bold transition flex items-center gap-2 shadow-sm shadow-red-500/20">
                                <i class="fa-solid fa-paper-plane"></i> Kirim Laporan
                            </button>
                            <a href="{{ route('company.reports.index') }}" class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-sm font-semibold transition">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
