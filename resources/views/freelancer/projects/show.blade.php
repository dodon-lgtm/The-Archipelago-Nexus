<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $project->project_name }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body{ font-family:'Plus Jakarta Sans',sans-serif; }
    </style>
</head>

@include('navbar.nav')

<body class="bg-slate-100">

<div class="max-w-7xl mx-auto py-10 px-6">

    <!-- Tombol Kembali -->
    <a href="{{ route('freelancer.projects.index') }}"
        class="inline-flex items-center gap-2 text-cyan-600 hover:text-cyan-700 font-semibold mb-6">
        <i class="fa fa-arrow-left"></i> Kembali
    </a>

    <div class="grid lg:grid-cols-3 gap-8">

        <!-- KIRI: Detail Utama -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow p-6">

                <!-- Pemanggilan Gambar dari Storage -->
                <img src="{{ $project->image ? asset('storage/'.$project->image) : asset('images/no-image.png') }}"
                     class="w-full h-80 object-cover rounded-xl" alt="Project Image">

                <h1 class="text-3xl font-bold mt-6">{{ $project->project_name }}</h1>

                <div class="flex flex-wrap gap-3 mt-4">
                    <span class="bg-cyan-100 text-cyan-700 px-3 py-1 rounded-full text-sm">
                        {{ $project->category->name ?? '-' }}
                    </span>
                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                        {{ ucfirst($project->status) }}
                    </span>
                </div>

                <div class="mt-8">
                    <h3 class="font-bold text-xl mb-3">Deskripsi Proyek</h3>
                    <p class="text-slate-600 leading-8">{{ $project->project_description }}</p>
                </div>

                @if($project->skills)
                <div class="mt-8">
                    <h3 class="font-bold mb-3">Skill yang Dibutuhkan</h3>
                    <div class="flex flex-wrap gap-2">
                      @foreach(explode(',', $project->skills) as $skill)
    <span class="bg-slate-100 px-3 py-2 rounded-lg text-sm">
        {{ trim($skill) }}
    </span>
@endforeach
                    </div>
                </div>
                @endif

                <!-- Lampiran -->
                <div class="mt-8">
                    <h3 class="font-bold text-xl mb-3">Lampiran Proyek</h3>
                    @if($project->attachment)
                        <div class="border rounded-xl p-4 flex items-center justify-between bg-slate-50">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-lg bg-red-100 flex items-center justify-center">
                                    <i class="fa-solid fa-file-pdf text-red-600 text-xl"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-sm truncate max-w-[200px]">{{ $project->attachment }}</p>
                                    <p class="text-xs text-slate-500">Lampiran proyek perusahaan</p>
                                </div>
                            </div>
                            <a href="{{ asset('storage/'.$project->attachment) }}" target="_blank"
                                class="bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded-lg text-sm">Download</a>
                        </div>
                    @else
                        <div class="border rounded-xl p-4 text-slate-500">Tidak ada lampiran.</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- KANAN: Sidebar Informasi -->
        <div>
            <div class="bg-white rounded-2xl shadow p-6 sticky top-6">
                <h2 class="font-bold text-xl mb-6">Informasi Proyek</h2>
                <div class="space-y-5">
                    <div>
                        <p class="text-sm text-slate-500">Budget</p>
                        <h3 class="text-2xl font-bold text-cyan-600">Rp {{ number_format($project->budget, 0, ',', '.') }}</h3>
                    </div>
                    <hr>
                    <div>
                        <p class="text-sm text-slate-500">Deadline</p>
                        <h3 class="font-semibold">{{ \Carbon\Carbon::parse($project->deadline)->format('d M Y') }}</h3>
                    </div>
                    <hr>
                    <div>
                        <p class="text-sm text-slate-500">Status</p>
                        <h3 class="font-semibold">{{ ucfirst($project->status) }}</h3>
                    </div>
                    <hr>
                    <div>
                        <p class="text-sm text-slate-500">Perusahaan</p>
                        <h3 class="font-semibold">{{ $project->owner->name ?? 'Tidak diketahui' }}</h3>
                    </div>
                </div>

                <div class="mt-8 space-y-3">
                    <a href="{{ route('freelancer.penawaran.create', $project)}}" class="w-full block bg-cyan-600 hover:bg-cyan-700 text-white text-center py-3 rounded-xl font-bold">
                        <i class="fa fa-paper-plane mr-2"></i> Kirim Penawaran
                    </a>

                    @php
                        $isSaved = $project->savedByFreelancers()
                            ->where('freelancer_id', auth()->id())
                            ->exists();
                    @endphp

                    @if($isSaved)
                        <form action="{{ route('freelancer.saved-projects.destroy', $project) }}" method="POST" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full border border-blue-300 bg-blue-50 text-blue-700 py-3 rounded-xl hover:bg-blue-100 font-semibold">
                                <i class="fa fa-bookmark mr-2"></i> Tersimpan
                            </button>
                        </form>
                    @else
                        <form action="{{ route('freelancer.saved-projects.store', $project) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="w-full border border-slate-300 py-3 rounded-xl hover:bg-slate-100">
                                <i class="fa fa-bookmark mr-2"></i> Simpan Proyek
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>