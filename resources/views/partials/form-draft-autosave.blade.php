{{--
    Auto-Save Draft Form — Freelancer & Company.

    Memuat public/js/form-draft-autosave.js hanya untuk user dengan role
    "freelancer" atau "company". Admin (dan role lain) tidak pernah memuat
    script ini maupun berubah perilakunya.

    Isolasi antar role memakai namespace localStorage terpisah lewat atribut
    data-draft-namespace pada tag <script>:
      - freelancer : vexus.fl.draft.v1:  (default engine, key "fl:")
      - company    : vexus.co.draft.v1:  (key "co:")
    Jadi draft Company tidak pernah menimpa / muncul di halaman Freelancer.

    Kontrak atribut pada Blade (lihat komentar di public/js/form-draft-autosave.js):
      data-draft-form, data-draft-key, data-draft-variant, data-draft-include,
      data-draft-skip, data-draft-submit-trigger, data-draft-clear,
      data-draft-clear-prefix, data-draft-namespace

    Penanda hapus draft dari controller (flash session):
      - session('draft_clear')         → key draft (string|array) yang dihapus
      - session('draft_clear_prefix')  → awalan key (string|array) yang dihapus
    Dipakai untuk aksi sukses yang redirect-nya kembali ke path yang sama
    (mis. kirim pesan workspace, tambah/ubah tahap), karena pada kondisi itu
    engine sengaja TIDAK menghapus draft (validation gagal tidak bisa
    dibedakan dari sukses tanpa penanda).

    Catatan: halaman freelancer & company saat ini adalah dokumen HTML mandiri
    (tidak memakai @extends), jadi partial ini di-include langsung di tiap
    halaman yang punya form draft.
--}}
@if (auth()->check() && auth()->user()->role === 'freelancer')
    <script src="{{ asset('js/form-draft-autosave.js') }}" defer></script>
@elseif (auth()->check() && auth()->user()->role === 'company')
    {{-- Namespace Company terpisah dari Freelancer (lihat docblock engine). --}}
    <script src="{{ asset('js/form-draft-autosave.js') }}" data-draft-namespace="vexus.co.draft.v1:" defer></script>
@endif

@php
    // Penanda hapus draft yang dikirim controller lewat flash session.
    $draftClearKeys = array_filter((array) session('draft_clear', []));
    $draftClearPrefixes = array_filter((array) session('draft_clear_prefix', []));
@endphp
@foreach ($draftClearKeys as $draftClearKey)
    <span hidden data-draft-clear="{{ $draftClearKey }}"></span>
@endforeach
@foreach ($draftClearPrefixes as $draftClearPrefix)
    <span hidden data-draft-clear-prefix="{{ $draftClearPrefix }}"></span>
@endforeach
