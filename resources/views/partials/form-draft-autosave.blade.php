{{--
    Auto-Save Draft Form — khusus role Freelancer.

    Memuat public/js/form-draft-autosave.js hanya untuk user dengan role
    "freelancer", sehingga Company dan Admin tidak pernah ikut memuat script
    maupun berubah perilakunya.

    Kontrak atribut pada Blade (lihat komentar di public/js/form-draft-autosave.js):
      data-draft-form, data-draft-key, data-draft-variant, data-draft-include,
      data-draft-skip, data-draft-submit-trigger, data-draft-clear,
      data-draft-clear-prefix

    Catatan: halaman freelancer saat ini adalah dokumen HTML mandiri (tidak
    memakai @extends), jadi partial ini di-include langsung di tiap halaman.
--}}
@if (auth()->check() && auth()->user()->role === 'freelancer')
    <script src="{{ asset('js/form-draft-autosave.js') }}" defer></script>
@endif
