<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workspace - {{ $workspace->project->project_name }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: { brand: '#2563EB', surface: '#F8FAFC' }
                }
            }
        }
    </script>
</head>

<body class="bg-surface text-slate-800 min-h-screen flex font-sans">

    @include('navbar.navigasi')

    <div class="flex-1 min-w-0 flex flex-col min-h-screen">
        @include('navbar.nav')

        <main class="flex-1 min-w-0 overflow-y-auto">
            <div class="max-w-7xl mx-auto px-6 py-8">

                {{-- Breadcrumb --}}
                <div class="flex items-center gap-2 text-sm text-slate-400 mb-6">
                    @if(auth()->user()->role === 'company')
                        <a href="{{ route('company.workspaces.index') }}" class="hover:text-brand transition">Workspace</a>
                    @else
                        <a href="{{ route('freelancer.workspaces.index') }}" class="hover:text-brand transition">Workspace Saya</a>
                    @endif
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    <span class="text-slate-600 font-medium">{{ $workspace->project->project_name }}</span>
                </div>

                @if(session('success'))
                    <div class="mb-6 flex items-center gap-3 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-medium">
                        <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 flex items-center gap-3 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm font-medium">
                        <i class="fa-solid fa-xmark-circle"></i> {{ session('error') }}
                    </div>
                @endif

                {{-- Layout 2 Kolom --}}
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

                    {{-- ============================================================
                         KOLOM KIRI (3/5) - CHAT
                    ============================================================ --}}
                    <div class="lg:col-span-3 bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden flex flex-col h-[calc(100vh-220px)]">

                        {{-- Chat Header --}}
                        <div class="px-5 py-4 border-b border-slate-100 bg-white flex items-center justify-between shrink-0">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center font-bold text-sm">
                                    {{ strtoupper(substr(auth()->user()->role === 'company' ? $workspace->freelancer->name : $workspace->company->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h3 class="font-bold text-sm text-slate-800">
                                        {{ auth()->user()->role === 'company' ? $workspace->freelancer->name : $workspace->company->name }}
                                    </h3>
                                    <p class="text-[10px] text-slate-400">{{ $workspace->project->project_name }}</p>
                                </div>
                            </div>
                            @php
                                $chatStatusColors = [
                                    'Sedang Dikerjakan' => 'bg-blue-500',
                                    'Menunggu Revisi' => 'bg-amber-500',
                                    'Selesai' => 'bg-emerald-500',
                                ];
                            @endphp
                            <span class="flex items-center gap-1.5 text-[10px] text-slate-500">
                                <span class="w-2 h-2 rounded-full {{ $chatStatusColors[$workspace->status] ?? 'bg-slate-400' }}"></span>
                                {{ $workspace->status }}
                            </span>
                        </div>

                        {{-- Chat Body --}}
                        <div id="chatBody" class="flex-1 overflow-y-auto p-5 space-y-4 bg-slate-50/50">
                            @if($workspace->messages->isNotEmpty())
                                @foreach($workspace->messages as $message)
                                    @if($message->type === 'system')
                                        <div class="flex justify-center">
                                            <div class="bg-white text-slate-400 text-[10px] font-medium px-4 py-2 rounded-full border border-slate-200 inline-flex items-center gap-2 shadow-sm">
                                                <i class="fa-solid fa-gear text-[9px]"></i>
                                                {{ $message->message }}
                                            </div>
                                        </div>
                                    @else
                                        @php $isMine = (int) $message->sender_id === (int) auth()->id(); @endphp
                                        <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }}">
                                            <div class="max-w-[80%] {{ $isMine ? 'bg-brand text-white' : 'bg-white text-slate-700 border border-slate-200' }} rounded-2xl px-4 py-3 shadow-sm">
                                                @if(!$isMine)
                                                    <p class="text-[10px] font-bold text-slate-400 mb-1">{{ $message->sender->name }}</p>
                                                @endif
                                                <p class="text-sm leading-relaxed">{{ $message->message }}</p>
                                                <p class="text-[9px] mt-1.5 {{ $isMine ? 'text-white/60' : 'text-slate-400' }} text-right">{{ $message->created_at->format('H:i, d M') }}</p>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <div class="flex flex-col items-center justify-center h-full py-12">
                                    <div class="w-16 h-16 mx-auto mb-4 bg-white rounded-2xl border border-slate-200 flex items-center justify-center">
                                        <i class="fa-regular fa-message text-2xl text-slate-300"></i>
                                    </div>
                                    <h3 class="text-sm font-bold text-slate-600">Belum Ada Pesan</h3>
                                    <p class="text-xs text-slate-400 mt-1">Mulai percakapan dengan mengirim pesan.</p>
                                </div>
                            @endif
                        </div>

                        {{-- Chat Input --}}
                        <div class="px-5 py-4 border-t border-slate-200 bg-white shrink-0">
                            <form method="POST" action="{{ route(auth()->user()->role === 'company' ? 'company.workspaces.message' : 'freelancer.workspaces.message', $workspace) }}" class="flex items-center gap-3">
                                @csrf
                                <input type="text" name="message" placeholder="Ketik pesan..." required maxlength="1000"
                                       class="flex-1 px-4 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand transition">
                                <button type="submit"
                                        class="px-5 py-2.5 bg-brand text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition flex items-center gap-2">
                                    <i class="fa-solid fa-paper-plane"></i>
                                    <span class="hidden sm:inline">Kirim</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- ============================================================
                         KOLOM KANAN (2/5) - PROGRESS
                    ============================================================ --}}
                    <div class="lg:col-span-2 space-y-6">

                        {{-- Card: Info Project --}}
                        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                            <div class="px-5 py-4 border-b border-slate-100">
                                <h2 class="font-bold text-sm text-slate-800">{{ $workspace->project->project_name }}</h2>
                            </div>
                            <div class="p-5 space-y-3 text-sm">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                        <i class="fa-solid fa-building text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-slate-400">Perusahaan</p>
                                        <p class="text-xs font-semibold text-slate-700">{{ $workspace->company->name }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-cyan-50 text-cyan-600 flex items-center justify-center shrink-0">
                                        <i class="fa-solid fa-user-tie text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-slate-400">Freelancer</p>
                                        <p class="text-xs font-semibold text-slate-700">{{ $workspace->freelancer->name }}</p>
                                    </div>
                                </div>
                                @if($workspace->project->deadline)
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center shrink-0">
                                        <i class="fa-regular fa-calendar text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-slate-400">Deadline</p>
                                        <p class="text-xs font-semibold text-slate-700">{{ \Carbon\Carbon::parse($workspace->project->deadline)->format('d M Y') }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- Card: Progress Bar Besar --}}
                        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                            <div class="px-5 py-4 border-b border-slate-100">
                                <h2 class="font-bold text-sm text-slate-800">Progress Pengerjaan</h2>
                            </div>
                            <div class="p-5">
                                <div class="text-center mb-4">
                                    <span class="text-4xl font-extrabold text-brand">{{ $progressValue }}%</span>
                                    @if($workspace->latestProgress)
                                        <p class="text-xs text-slate-400 mt-1">{{ $workspace->latestProgress->stage }}</p>
                                    @endif
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-4 overflow-hidden">
                                    <div class="h-full rounded-full bg-gradient-to-r from-brand to-cyan-400 transition-all duration-700" style="width: {{ $progressValue }}%"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Card: Stage --}}
                        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                            <div class="px-5 py-4 border-b border-slate-100">
                                <h2 class="font-bold text-sm text-slate-800">Tahap Pengerjaan</h2>
                            </div>
                            <div class="p-5">
                                <div class="grid grid-cols-2 gap-2">
                                    @foreach($allStages as $index => $stage)
                                        @php
                                            $isCompleted = $index < $activeStageIndex;
                                            $isActive = $index === $activeStageIndex;
                                            if ($isCompleted) {
                                                $icon = 'fa-solid fa-check-circle';
                                                $color = 'text-emerald-500';
                                                $bg = 'bg-emerald-50';
                                                $label = 'Selesai';
                                                $labelColor = 'text-emerald-600 bg-emerald-100';
                                            } elseif ($isActive) {
                                                $icon = 'fa-solid fa-play-circle';
                                                $color = 'text-brand';
                                                $bg = 'bg-blue-50';
                                                $label = 'Aktif';
                                                $labelColor = 'text-white bg-brand';
                                            } else {
                                                $icon = 'fa-regular fa-circle';
                                                $color = 'text-slate-300';
                                                $bg = 'bg-slate-50';
                                                $label = '';
                                                $labelColor = '';
                                            }
                                        @endphp
                                        <div class="{{ $bg }} border border-slate-100 rounded-xl p-3 flex items-center gap-2 {{ $isActive ? 'ring-2 ring-brand/20' : '' }}">
                                            <i class="{{ $icon }} {{ $color }} text-lg"></i>
                                            <div class="min-w-0">
                                                <p class="text-xs font-semibold {{ $isCompleted ? 'text-emerald-700' : ($isActive ? 'text-brand' : 'text-slate-400') }} truncate">{{ $stage }}</p>
                                                @if($label)
                                                    <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-full {{ $labelColor }}">{{ $label }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Card: Timeline --}}
                        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                            <div class="px-5 py-4 border-b border-slate-100">
                                <h2 class="font-bold text-sm text-slate-800">Timeline Progress</h2>
                            </div>
                            <div class="p-5">
                                @if($workspace->progressHistories->isNotEmpty())
                                    <div class="space-y-4">
                                        @foreach($workspace->progressHistories as $history)
                                            <div class="relative pl-6 border-l-2 border-slate-200">
                                                <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-brand flex items-center justify-center">
                                                    <span class="text-[8px] text-white font-bold">{{ $history->progress }}%</span>
                                                </div>
                                                <div class="bg-slate-50 rounded-xl p-3 ml-2">
                                                    <div class="flex items-center justify-between gap-2 mb-1">
                                                        <span class="text-xs font-bold text-slate-700">{{ $history->stage }}</span>
                                                        <span class="text-[10px] text-slate-400">{{ $history->created_at->format('d M Y') }}</span>
                                                    </div>
                                                    @if($history->description)
                                                        <p class="text-xs text-slate-500 leading-relaxed">{{ $history->description }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="py-8 text-center">
                                        <p class="text-xs text-slate-400">Belum ada riwayat progress.</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Card: Actions --}}
                        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                            <div class="px-5 py-4">
                                @if(auth()->user()->role === 'freelancer')
                                    {{-- Update Progress Button (Freelancer Only) --}}
                                    <button type="button" onclick="document.getElementById('progressModal').classList.remove('hidden')"
                                            class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-brand text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
                                        <i class="fa-solid fa-chart-line"></i> Update Progress
                                    </button>
                                @endif

                                @if(auth()->user()->role === 'company' && $progressValue == 100 && $workspace->status !== 'Selesai')
                                    {{-- Confirm Completion Button (Company Only) --}}
                                    <form method="POST" action="{{ route('company.workspaces.complete', $workspace) }}"
                                          onsubmit="return confirm('Konfirmasi bahwa pekerjaan telah selesai?')">
                                        @csrf
                                        <button type="submit"
                                                class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-500 text-white rounded-xl text-sm font-semibold hover:bg-emerald-600 transition">
                                            <i class="fa-solid fa-check-circle"></i> Konfirmasi Pekerjaan Selesai
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </main>

        @include('navbar.footer')
    </div>

    {{-- ============================================================
         MODAL UPDATE PROGRESS
    ============================================================ --}}
    <div id="progressModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-slate-800">Update Progress</h3>
                <button type="button" onclick="document.getElementById('progressModal').classList.add('hidden')" class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center hover:bg-slate-200 transition">
                    <i class="fa-solid fa-xmark text-slate-500"></i>
                </button>
            </div>
            <form method="POST" action="{{ route('freelancer.workspaces.progress', $workspace) }}" class="p-6 space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Stage</label>
                    <select name="stage" required
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand/30">
                        @foreach($allStages as $stage)
                            <option value="{{ $stage }}" {{ $activeStage === $stage ? 'selected' : '' }}>{{ $stage }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Progress (0-100%)</label>
                    <input type="number" name="progress" min="0" max="100" value="{{ $progressValue }}" required
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand/30">
                    <p class="text-[10px] text-slate-400 mt-1">Progress minimal: {{ $progressValue }}% (tidak boleh turun)</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Deskripsi</label>
                    <textarea name="description" rows="3" maxlength="500"
                              class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 resize-none"
                              placeholder="Jelaskan update progress..."></textarea>
                </div>

                <button type="submit"
                        class="w-full py-2.5 bg-brand text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Progress
                </button>
            </form>
        </div>
    </div>

    <script>
        // Auto scroll chat ke bawah
        document.addEventListener('DOMContentLoaded', function() {
            const chatBody = document.getElementById('chatBody');
            if (chatBody) {
                chatBody.scrollTop = chatBody.scrollHeight;
            }
        });
    </script>

</body>
</html>

