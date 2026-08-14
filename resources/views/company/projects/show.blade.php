<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        }
    </script>

    <title>{{ $project->project_name }} - Detail Proyek</title>

    {{-- Google Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    {{-- Alpine.js (Untuk Filter, Search, & Toggle tanpa reload) --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8FAFC;
        }

        /* Custom scrollbar halus untuk area daftar penawaran */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #CBD5E1;
            border-radius: 9999px;
        }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #334155;
        }
    </style>
</head>

<body class="text-slate-800 bg-slate-50 min-h-screen flex dark:bg-slate-950 dark:text-slate-100 transition-colors duration-200">

    {{-- =====================================================
        SIDEBAR
    ====================================================== --}}
    @include('navbar.navigasi')

    {{-- =====================================================
        AREA UTAMA
    ====================================================== --}}
    <div class="flex-1 min-w-0 flex flex-col min-h-screen">

        {{-- NAVBAR ATAS --}}
        @include('navbar.nav')

        {{-- KONTEN UTAMA --}}
        <main class="flex-1 min-w-0 overflow-y-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

                {{-- BREADCRUMB & NAVIGASI --}}
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-2 text-xs font-medium text-slate-500 dark:text-slate-400">
                        <a href="{{ route('company.dashboard') }}" class="hover:text-blue-600 transition-colors">Dashboard</a>
                        <i class="fa-solid fa-chevron-right text-[9px] text-slate-400"></i>
                        <a href="{{ route('company.projects.index') }}" class="hover:text-blue-600 transition-colors">Proyek Saya</a>
                        <i class="fa-solid fa-chevron-right text-[9px] text-slate-400"></i>
                        <span class="text-slate-800 font-semibold dark:text-slate-200">Detail</span>
                    </div>

                    <a href="{{ route('company.projects.index') }}"
                       class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-slate-200 dark:bg-slate-900 dark:border-slate-800 text-slate-700 dark:text-slate-300 rounded-lg text-xs font-semibold hover:bg-slate-50 dark:hover:bg-slate-800 transition-all shadow-sm">
                        <i class="fa-solid fa-arrow-left text-[11px]"></i>
                        Kembali
                    </a>
                </div>

                {{-- FLASH MESSAGE --}}
                @if(session('success'))
                    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 dark:bg-emerald-950/50 dark:border-emerald-800 dark:text-emerald-300 rounded-xl text-sm flex items-center gap-3 shadow-sm">
                        <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400 text-lg"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 dark:bg-rose-950/50 dark:border-rose-800 dark:text-rose-300 rounded-xl text-sm flex items-center gap-3 shadow-sm">
                        <i class="fa-solid fa-circle-xmark text-rose-600 dark:text-rose-400 text-lg"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                {{-- =========================================================
                    HEADER KARTU PROYEK (BERSIH & RINGKAS)
                ========================================================== --}}
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 sm:p-6 shadow-sm space-y-5">
                    
                    {{-- Judul, Category & Action Buttons --}}
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 pb-4 border-b border-slate-100 dark:border-slate-800">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white">
                                    {{ $project->project_name }}
                                </h1>
                                
                                @php
                                    $status = strtolower($project->status ?? 'open');
                                @endphp
                                @if($status === 'open')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/50 dark:text-emerald-400 dark:border-emerald-800 text-xs font-semibold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Open
                                    </span>
                                @elseif($status === 'closed')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 border border-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:border-slate-700 text-xs font-semibold">
                                        Closed
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950/50 dark:text-amber-400 dark:border-amber-800 text-xs font-semibold">
                                        {{ ucfirst($status) }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                Kategori: <span class="font-medium text-slate-700 dark:text-slate-300">{{ $project->category->name ?? 'Umum' }}</span>
                            </p>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center gap-2">
                            <a href="{{ route('company.projects.edit', $project) }}" 
                               class="inline-flex items-center gap-1.5 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold transition-all shadow-sm">
                                <i class="fa-solid fa-pen-to-square"></i>
                                Edit Proyek
                            </a>

                            <form method="POST" action="{{ route('company.projects.destroy', $project) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus proyek ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-2 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 dark:bg-rose-950/40 dark:hover:bg-rose-900/60 dark:text-rose-300 dark:border-rose-900 rounded-lg text-xs font-semibold transition-all">
                                    <i class="fa-solid fa-trash"></i>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Ringkasan Parameter Proyek (Grid Horizontal 3 Kolom) --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="p-3.5 bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 rounded-xl flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 flex items-center justify-center text-sm shrink-0">
                                <i class="fa-solid fa-wallet"></i>
                            </div>
                            <div>
                                <p class="text-[11px] font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Budget Proyek</p>
                                <p class="text-sm font-bold text-slate-900 dark:text-white mt-0.5">
                                    {{ $project->budget ? 'Rp ' . number_format($project->budget, 0, ',', '.') : 'Belum Ditentukan' }}
                                </p>
                            </div>
                        </div>

                        <div class="p-3.5 bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 rounded-xl flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-sky-100 dark:bg-sky-900/50 text-sky-600 dark:text-sky-400 flex items-center justify-center text-sm shrink-0">
                                <i class="fa-regular fa-calendar-check"></i>
                            </div>
                            <div>
                                <p class="text-[11px] font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tenggat Waktu</p>
                                <p class="text-sm font-bold text-slate-900 dark:text-white mt-0.5">
                                    {{ $project->deadline ? \Carbon\Carbon::parse($project->deadline)->format('d M Y') : 'Belum Ditentukan' }}
                                </p>
                            </div>
                        </div>

                        <div class="p-3.5 bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 rounded-xl flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-sm shrink-0">
                                <i class="fa-solid fa-users"></i>
                            </div>
                            <div>
                                <p class="text-[11px] font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Penawaran</p>
                                <p class="text-sm font-bold text-slate-900 dark:text-white mt-0.5">
                                    {{ $project->penawarans->count() }} Freelancer
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Deskripsi Ringkas --}}
                    <div x-data="{ expanded: false }" class="pt-2">
                        <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                            <i class="fa-solid fa-align-left text-blue-500"></i> Deskripsi Proyek
                        </p>
                        <div class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-800/30 p-4 rounded-xl border border-slate-100 dark:border-slate-800 leading-relaxed relative">
                            <div :class="expanded ? '' : 'line-clamp-3'">
                                {!! nl2br(e($project->project_description ?? 'Tidak ada deskripsi proyek.')) !!}
                            </div>
                            @if(strlen($project->project_description) > 200)
                                <button @click="expanded = !expanded" class="text-xs font-semibold text-blue-600 dark:text-blue-400 hover:underline mt-2 inline-block">
                                    <span x-text="expanded ? 'Sembunyikan' : 'Baca Selengkapnya...'"></span>
                                </button>
                            @endif
                        </div>
                    </div>

                </div>

                {{-- =========================================================
                    SECTION PENAWARAN FREELANCER (DENGAN FILTER & COMPACT CARD)
                ========================================================== --}}
                @php
                    $hasAccepted = $project->penawarans->contains(fn($p) => $p->status === 'Diterima');
                    
                    // Siapkan array JSON untuk Alpine.js
                    $penawaranData = $project->penawarans->map(function($p) {
                        $foto = optional($p->freelancer->freelanceProfile)->photo;
                        $photoUrl = $foto ? (Str::startsWith($foto, ['http://', 'https://']) ? $foto : asset('storage/' . $foto)) : null;

                        return [
                            'id' => $p->id,
                            'freelancer_id' => $p->freelancer_id,
                            'freelancer_name' => $p->freelancer->name ?? 'Tidak diketahui',
                            'freelancer_photo' => $photoUrl,
                            'status' => $p->status,
                            'harga' => (int) $p->harga_penawaran,
                            'harga_formatted' => number_format($p->harga_penawaran, 0, ',', '.'),
                            'estimasi' => (int) $p->estimasi_hari,
                            'pesan' => $p->pesan ?? '',
                            'proposal' => $p->proposal ? asset('storage/' . $p->proposal) : null,
                            'selected_at' => $p->selected_at ? $p->selected_at->format('d M Y H:i') : null,
                            'created_at_timestamp' => $p->created_at ? $p->created_at->timestamp : 0,
                        ];
                    });
                @endphp

                <div x-data="{
                        statusFilter: 'all',
                        searchQuery: '',
                        sortBy: 'latest',
                        items: {{ json_encode($penawaranData) }},
                        
                        get filteredItems() {
                            return this.items.filter(item => {
                                const matchStatus = this.statusFilter === 'all' || item.status.toLowerCase() === this.statusFilter.toLowerCase();
                                const matchSearch = item.freelancer_name.toLowerCase().includes(this.searchQuery.toLowerCase());
                                return matchStatus && matchSearch;
                            }).sort((a, b) => {
                                if (this.sortBy === 'price_asc') return a.harga - b.harga;
                                if (this.sortBy === 'price_desc') return b.harga - a.harga;
                                if (this.sortBy === 'days_asc') return a.estimasi - b.estimasi;
                                return b.created_at_timestamp - a.created_at_timestamp; // latest
                            });
                        }
                    }" 
                     class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden space-y-0">
                    
                    {{-- Header Penawaran + Toolbar Filter --}}
                    <div class="p-5 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900 space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                    <i class="fa-solid fa-paper-plane text-blue-600 dark:text-blue-400"></i>
                                    Penawaran Masuk
                                </h2>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                    Kelola dan tinjau penawaran dari para freelancer.
                                </p>
                            </div>

                            <div class="text-xs font-semibold px-3 py-1.5 bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-950/50 dark:text-blue-300 dark:border-blue-800 rounded-lg w-fit">
                                Total: <span x-text="filteredItems.length"></span> Penawaran
                            </div>
                        </div>

                        {{-- Control Bar (Search, Filter Tabs, Sort) --}}
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 pt-2">
                            {{-- Search Box --}}
                            <div class="md:col-span-4 relative">
                                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                <input type="text" 
                                       x-model="searchQuery" 
                                       placeholder="Cari nama freelancer..." 
                                       class="w-full pl-9 pr-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none dark:text-white">
                            </div>

                            {{-- Filter Status Pills --}}
                            <div class="md:col-span-5 flex items-center gap-1 overflow-x-auto pb-1 md:pb-0">
                                <button @click="statusFilter = 'all'" 
                                        :class="statusFilter === 'all' ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200'"
                                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all whitespace-nowrap">
                                    Semua
                                </button>
                                <button @click="statusFilter = 'menunggu'" 
                                        :class="statusFilter === 'menunggu' ? 'bg-amber-500 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200'"
                                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all whitespace-nowrap">
                                    Menunggu
                                </button>
                                <button @click="statusFilter = 'diterima'" 
                                        :class="statusFilter === 'diterima' ? 'bg-emerald-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200'"
                                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all whitespace-nowrap">
                                    Diterima
                                </button>
                                <button @click="statusFilter = 'ditolak'" 
                                        :class="statusFilter === 'ditolak' ? 'bg-rose-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200'"
                                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all whitespace-nowrap">
                                    Ditolak
                                </button>
                            </div>

                            {{-- Sort Dropdown --}}
                            <div class="md:col-span-3">
                                <select x-model="sortBy" class="w-full py-2 px-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none dark:text-white font-medium">
                                    <option value="latest">Terbaru</option>
                                    <option value="price_asc">Harga Terendah</option>
                                    <option value="price_desc">Harga Tertinggi</option>
                                    <option value="days_asc">Waktu Tercepat</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Daftar Penawaran (Daftar Ringkas) --}}
                    <div class="p-4 sm:p-5 space-y-3 max-h-[680px] overflow-y-auto custom-scrollbar">
                        
                        {{-- Empty State jika tidak ada data sama sekali --}}
                        @if ($project->penawarans->isEmpty())
                            <div class="py-12 text-center">
                                <div class="w-12 h-12 mx-auto mb-3 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center text-slate-400">
                                    <i class="fa-regular fa-folder-open text-xl"></i>
                                </div>
                                <h3 class="text-sm font-bold text-slate-700 dark:text-slate-300">Belum Ada Penawaran</h3>
                                <p class="text-xs text-slate-400 mt-1">Penawaran dari freelancer akan muncul di sini.</p>
                            </div>
                        @else
                            {{-- Empty state jika hasil filter kosong --}}
                            <template x-if="filteredItems.length === 0">
                                <div class="py-12 text-center">
                                    <p class="text-xs font-semibold text-slate-400">Tidak ada penawaran yang cocok dengan filter atau pencarian Anda.</p>
                                </div>
                            </template>

                            {{-- Loop Alpine.js untuk menampilkan item secara ringkas --}}
                            <template x-for="item in filteredItems" :key="item.id">
                                <div x-data="{ showMessage: false }" 
                                     class="p-4 border border-slate-200 dark:border-slate-800 hover:border-blue-300 dark:hover:border-blue-800 rounded-xl bg-white dark:bg-slate-900 transition-all shadow-sm space-y-3">
                                    
                                    {{-- Row Atas: Profil + Info Penawaran + Status --}}
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                        
                                        {{-- Freelancer Profile --}}
                                        <div class="flex items-center gap-3">
                                            <template x-if="item.freelancer_photo">
                                                <a :href="'/company/freelancers/' + item.freelancer_id" class="shrink-0">
                                                    <img :src="item.freelancer_photo" :alt="item.freelancer_name" class="w-10 h-10 rounded-full object-cover border border-slate-200 dark:border-slate-700 hover:opacity-90">
                                                </a>
                                            </template>
                                            <template x-if="!item.freelancer_photo">
                                                <a :href="'/company/freelancers/' + item.freelancer_id" class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm shrink-0">
                                                    <span x-text="item.freelancer_name.charAt(0).toUpperCase()"></span>
                                                </a>
                                            </template>

                                            <div>
                                                <a :href="'/company/freelancers/' + item.freelancer_id" class="text-sm font-bold text-slate-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors" x-text="item.freelancer_name"></a>
                                                <p class="text-[11px] text-slate-400">Freelancer</p>
                                            </div>
                                        </div>

                                        {{-- Ringkasan Angka (Harga & Estimasi) --}}
                                        <div class="flex items-center gap-4 bg-slate-50 dark:bg-slate-800/60 px-3 py-1.5 rounded-lg border border-slate-100 dark:border-slate-800">
                                            <div>
                                                <p class="text-[10px] text-slate-400 uppercase font-medium">Penawaran</p>
                                                <p class="text-xs font-bold text-blue-600 dark:text-blue-400" x-text="'Rp ' + item.harga_formatted"></p>
                                            </div>
                                            <div class="h-6 w-px bg-slate-200 dark:bg-slate-700"></div>
                                            <div>
                                                <p class="text-[10px] text-slate-400 uppercase font-medium">Waktu</p>
                                                <p class="text-xs font-bold text-slate-700 dark:text-slate-200" x-text="item.estimasi + ' Hari'"></p>
                                            </div>
                                        </div>

                                        {{-- Badge Status --}}
                                        <div>
                                            <template x-if="item.status === 'Menunggu'">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950/50 dark:text-amber-400 dark:border-amber-900 text-xs font-semibold">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                    Menunggu
                                                </span>
                                            </template>
                                            <template x-if="item.status === 'Diterima'">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/50 dark:text-emerald-400 dark:border-emerald-900 text-xs font-semibold">
                                                    <i class="fa-solid fa-check text-[10px]"></i>
                                                    Terpilih
                                                </span>
                                            </template>
                                            <template x-if="item.status === 'Ditolak'">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-950/50 dark:text-rose-400 dark:border-rose-900 text-xs font-semibold">
                                                    <i class="fa-solid fa-xmark text-[10px]"></i>
                                                    Ditolak
                                                </span>
                                            </template>
                                        </div>
                                    </div>

                                    {{-- Collapsible Pesan Freelancer --}}
                                    <template x-if="item.pesan">
                                        <div class="pt-1">
                                            <button @click="showMessage = !showMessage" class="text-[11px] font-semibold text-slate-500 hover:text-blue-600 dark:text-slate-400 flex items-center gap-1">
                                                <i class="fa-regular fa-comment-dots"></i>
                                                <span x-text="showMessage ? 'Sembunyikan Pesan' : 'Lihat Pesan Freelancer'"></span>
                                            </button>
                                            
                                            <div x-show="showMessage" x-collapse class="mt-2 p-3 bg-slate-50 dark:bg-slate-800/40 rounded-lg text-xs text-slate-600 dark:text-slate-300 border border-slate-100 dark:border-slate-800 leading-relaxed">
                                                <p x-text="item.pesan"></p>
                                            </div>
                                        </div>
                                    </template>

                                    {{-- Row Bawah: Proposal Link & Actions --}}
                                    <div class="flex items-center justify-between pt-2 border-t border-slate-100 dark:border-slate-800">
                                        {{-- Proposal File --}}
                                        <div>
                                            <template x-if="item.proposal">
                                                <a :href="item.proposal" target="_blank" class="inline-flex items-center gap-1.5 text-xs text-blue-600 dark:text-blue-400 font-medium hover:underline">
                                                    <i class="fa-regular fa-file-pdf"></i>
                                                    Lihat Proposal
                                                </a>
                                            </template>
                                            <template x-if="!item.proposal">
                                                <span class="text-[11px] text-slate-400">Tidak ada lampiran proposal</span>
                                            </template>
                                        </div>

                                        {{-- Action Buttons --}}
                                        <div class="flex items-center gap-2">
                                            {{-- Tombol Laporkan --}}
                                            <a :href="'/company/reports/create?penawaran_id=' + item.id" 
                                               class="p-1.5 text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 transition-colors" 
                                               title="Laporkan penawaran">
                                                <i class="fa-regular fa-flag text-xs"></i>
                                            </a>

                                            {{-- Jika Status Menunggu & Belum Ada yang Diterima --}}
                                            @if(!$hasAccepted)
                                                <template x-if="item.status === 'Menunggu'">
                                                    <form method="POST" :action="'/company/projects/{{ $project->id }}/penawaran/' + item.id + '/select'" onsubmit="return confirm('Pilih freelancer ini? Penawaran lain akan otomatis ditolak.');">
                                                        @csrf
                                                        <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold transition-all shadow-sm flex items-center gap-1.5">
                                                            <i class="fa-solid fa-check text-[10px]"></i>
                                                            Pilih Freelancer
                                                        </button>
                                                    </form>
                                                </template>
                                            @endif

                                            {{-- Jika Status Diterima --}}
                                            <template x-if="item.status === 'Diterima'">
                                                <div class="flex items-center gap-2">
                                                    @if($project->workspace)
                                                        <a href="{{ route('company.workspaces.show', $project->workspace) }}" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold transition-all shadow-sm flex items-center gap-1.5">
                                                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                                            Buka Workspace
                                                        </a>
                                                    @endif
                                                </div>
                                            </template>
                                        </div>
                                    </div>

                                </div>
                            </template>
                        @endif

                    </div>
                </div>

            </div>
        </main>
    </div>

</body>
</html>