{{-- =====================================================
    FORM KONTAK EMAIL — HUBUNGI KAMI VIA EMAIL
    Di-include oleh halaman Pusat Bantuan (help/index).
    Form mengirim ke route('help.contact') dan diproses
    oleh HelpCenterController@storeContact via Laravel Mail.
====================================================== --}}

@php
    // Guard: $errors selalu tersedia di request web (ShareErrorsFromSession),
    // tetapi dijamin aman bila partial dirender di luar konteks session.
    $errors = $errors ?? new \Illuminate\Support\ViewErrorBag;

    $authRole = Auth::user()->role ?? null;
    $roleDisplay = match ($authRole) {
        'freelancer' => 'Freelancer',
        'company'    => 'Company',
        'admin'      => 'Admin',
        default      => 'Pengunjung (Belum Masuk)',
    };
    $roleDot = match ($authRole) {
        'freelancer' => 'bg-blue-500',
        'company'    => 'bg-indigo-500',
        'admin'      => 'bg-emerald-500',
        default      => 'bg-slate-400',
    };
@endphp

<div id="hubungi-kami" class="relative z-10 mt-10 scroll-mt-28 {{ (old('name') !== null || old('subject') !== null || $errors->any() || session('success') || session('error')) ? '' : 'hidden' }}">

    <div class="rounded-3xl bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 shadow-2xl shadow-blue-900/20 overflow-hidden">

        {{-- HEADER KARTU --}}
        <div class="px-7 sm:px-10 pt-8 pb-6 border-b border-slate-100 dark:border-slate-700 flex items-start gap-4">
            <div class="w-12 h-12 shrink-0 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 text-white flex items-center justify-center shadow-lg shadow-blue-500/30">
                <i class="fa-solid fa-envelope text-xl"></i>
            </div>

            <div class="flex-1">
                <h3 class="text-xl font-black text-slate-900 dark:text-slate-100">
                    Kirim Pesan via Email
                </h3>
                <p class="mt-1 text-sm leading-6 text-slate-500 dark:text-slate-300">
                    Lengkapi formulir di bawah. Pesan akan dikirim langsung ke
                    tim ApexForge Labs dan kami akan meninjau serta membalas melalui email Anda.
                </p>
            </div>

            <button type="button" id="closeContactForm"
                class="shrink-0 w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors flex items-center justify-center"
                title="Tutup formulir">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        {{-- FLASH MESSAGE: SUKSES --}}
        @if (session('success'))
            <div class="mx-7 sm:mx-10 mt-7 rounded-2xl border border-emerald-200 bg-emerald-50 dark:border-emerald-500/30 dark:bg-emerald-500/10 px-5 py-4 flex items-start gap-3">
                <i class="fa-solid fa-circle-check text-emerald-500 dark:text-emerald-400 text-lg mt-0.5"></i>
                <p class="text-sm font-semibold leading-6 text-emerald-700 dark:text-emerald-300">
                    {{ session('success') }}
                </p>
            </div>
        @endif

        {{-- FLASH MESSAGE: GAGAL --}}
        @if (session('error'))
            <div class="mx-7 sm:mx-10 mt-7 rounded-2xl border border-rose-200 bg-rose-50 dark:border-rose-500/30 dark:bg-rose-500/10 px-5 py-4 flex items-start gap-3">
                <i class="fa-solid fa-circle-exclamation text-rose-500 dark:text-rose-400 text-lg mt-0.5"></i>
                <p class="text-sm font-semibold leading-6 text-rose-700 dark:text-rose-300">
                    {{ session('error') }}
                </p>
            </div>
        @endif

        {{-- FORM --}}
        <form method="POST" action="{{ route('help.contact') }}" novalidate class="px-7 sm:px-10 py-8">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                {{-- NAMA --}}
                <div>
                    <label for="contactName" class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-2">
                        <i class="fa-solid fa-user mr-1.5 text-blue-500 dark:text-blue-400"></i>
                        Nama Lengkap
                    </label>
                    <input type="text" id="contactName" name="name" value="{{ old('name', Auth::user()->name ?? '') }}" placeholder="Nama Lengkap"
                        class="w-full rounded-xl border px-4 py-3 text-sm font-medium bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 outline-none transition-all duration-200 focus:bg-white dark:focus:bg-slate-900 focus:ring-4 {{ $errors->has('name') ? 'border-rose-300 dark:border-rose-500/60 focus:border-rose-400 focus:ring-rose-100 dark:focus:ring-rose-500/10' : 'border-slate-200 dark:border-slate-600 focus:border-blue-400 focus:ring-blue-100 dark:focus:ring-blue-500/10' }}">
                    @error('name')
                        <p class="mt-2 text-xs font-bold text-rose-600 dark:text-rose-400 flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-exclamation"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- EMAIL --}}
                <div>
                    <label for="contactEmail" class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-2">
                        <i class="fa-solid fa-envelope mr-1.5 text-blue-500 dark:text-blue-400"></i>
                        Alamat Email
                    </label>
                    <input type="email" id="contactEmail" name="email" value="{{ old('email', Auth::user()->email ?? '') }}" placeholder="Alamat Email"
                        class="w-full rounded-xl border px-4 py-3 text-sm font-medium bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 outline-none transition-all duration-200 focus:bg-white dark:focus:bg-slate-900 focus:ring-4 {{ $errors->has('email') ? 'border-rose-300 dark:border-rose-500/60 focus:border-rose-400 focus:ring-rose-100 dark:focus:ring-rose-500/10' : 'border-slate-200 dark:border-slate-600 focus:border-blue-400 focus:ring-blue-100 dark:focus:ring-blue-500/10' }}">
                    @error('email')
                        <p class="mt-2 text-xs font-bold text-rose-600 dark:text-rose-400 flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-exclamation"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- ROLE (OTOMATIS DARI AKUN) --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-2">
                        <i class="fa-solid fa-user-tag mr-1.5 text-blue-500 dark:text-blue-400"></i>
                        Role Akun
                    </label>
                    <div class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-100 dark:bg-slate-800 px-4 py-3 text-sm font-bold text-slate-600 dark:text-slate-300 flex items-center gap-2.5">
                        <span class="w-2.5 h-2.5 rounded-full {{ $roleDot }}"></span>
                        {{ $roleDisplay }}
                    </div>
                    <p class="mt-1.5 text-xs text-slate-400 dark:text-slate-400">
                        Terisi otomatis dari akun yang sedang masuk.
                    </p>
                </div>

                {{-- KATEGORI --}}
                <div>
                    <label for="contactCategory" class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-2">
                        <i class="fa-solid fa-tags mr-1.5 text-blue-500 dark:text-blue-400"></i>
                        Kategori
                    </label>
                    <select id="contactCategory" name="category"
                        class="w-full rounded-xl border px-4 py-3 text-sm font-medium bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100 outline-none transition-all duration-200 focus:bg-white dark:focus:bg-slate-900 focus:ring-4 {{ $errors->has('category') ? 'border-rose-300 dark:border-rose-500/60 focus:border-rose-400 focus:ring-rose-100 dark:focus:ring-rose-500/10' : 'border-slate-200 dark:border-slate-600 focus:border-blue-400 focus:ring-blue-100 dark:focus:ring-blue-500/10' }}">
                        <option value="" disabled {{ old('category') === null ? 'selected' : '' }}>Pilih kategori</option>
                        @foreach ($contactCategories as $value => $label)
                            <option value="{{ $value }}" {{ old('category') === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('category')
                        <p class="mt-2 text-xs font-bold text-rose-600 dark:text-rose-400 flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-exclamation"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

            </div>
{{-- SUBJEK --}}
            <div class="mt-5">
                <label for="contactSubject" class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-2">
                    <i class="fa-solid fa-heading mr-1.5 text-blue-500 dark:text-blue-400"></i>
                    Subjek
                </label>
                <input type="text" id="contactSubject" name="subject" value="{{ old('subject') }}" placeholder="Subjek pesan"
                    class="w-full rounded-xl border px-4 py-3 text-sm font-medium bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 outline-none transition-all duration-200 focus:bg-white dark:focus:bg-slate-900 focus:ring-4 {{ $errors->has('subject') ? 'border-rose-300 dark:border-rose-500/60 focus:border-rose-400 focus:ring-rose-100 dark:focus:ring-rose-500/10' : 'border-slate-200 dark:border-slate-600 focus:border-blue-400 focus:ring-blue-100 dark:focus:ring-blue-500/10' }}">
                @error('subject')
                    <p class="mt-2 text-xs font-bold text-rose-600 dark:text-rose-400 flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-exclamation"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            {{-- PESAN --}}
            <div class="mt-5">
                <label for="contactMessage" class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-2">
                    <i class="fa-solid fa-comment-dots mr-1.5 text-blue-500 dark:text-blue-400"></i>
                    Pesan
                </label>
                <textarea id="contactMessage" name="message" rows="6" placeholder="Jelaskan pertanyaan atau kendala yang Anda alami..."
                    class="w-full rounded-xl border px-4 py-3 text-sm font-medium bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 outline-none transition-all duration-200 focus:bg-white dark:focus:bg-slate-900 focus:ring-4 resize-y {{ $errors->has('message') ? 'border-rose-300 dark:border-rose-500/60 focus:border-rose-400 focus:ring-rose-100 dark:focus:ring-rose-500/10' : 'border-slate-200 dark:border-slate-600 focus:border-blue-400 focus:ring-blue-100 dark:focus:ring-blue-500/10' }}">{{ old('message') }}</textarea>
                @error('message')
                    <p class="mt-2 text-xs font-bold text-rose-600 dark:text-rose-400 flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-exclamation"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            {{-- SUBMIT --}}
            <div class="mt-7 pt-6 border-t border-slate-100 dark:border-slate-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <p class="text-xs text-slate-400 dark:text-slate-400 flex items-center gap-1.5">
                    <i class="fa-solid fa-shield-halved text-blue-500 dark:text-blue-400"></i>
                    Pesan hanya dibaca oleh tim ApexForge Labs.
                </p>

                <button type="submit"
                    class="inline-flex items-center justify-center gap-3 px-8 py-4 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-extrabold shadow-lg shadow-blue-500/30 hover:shadow-xl hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200">
                    <i class="fa-solid fa-paper-plane"></i>
                    Kirim Pesan
                </button>
            </div>

        </form>
    </div>
</div>