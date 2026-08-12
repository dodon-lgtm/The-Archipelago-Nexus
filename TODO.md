# TODO — Arsip Proyek + Lifecycle Project (Company)

## Fase 1 — Migration & Model
- [x] Migration `add_archive_status_to_projects_table` (enum active/archived/inactive, default active)
- [x] `app/Models/Project.php`: `$fillable` + helper `archive()/activate()/deactivate()/isArchived()` + update `acceptsOffers()`

## Fase 2 — Backend Controller & Routes
- [x] `app/Http/Controllers/Company/ProjectController.php`: `index()` filter active; `archiveIndex()`, `archive()`, `activate()`, `deactivate()`; perkuat `destroy()`
- [x] `routes/web.php`: route arsip (di atas show)

## Fase 3 — Views
- [ ] `resources/views/company/projects/index.blade.php`: filter active + link arsip
- [ ] `resources/views/company/projects/archive.blade.php`: halaman arsip baru
- [ ] `resources/views/company/projects/show.blade.php`: tombol arsip/aktifkan/nonaktifkan; sembunyikan Delete untuk project ber-workflow
- [x] `resources/views/navbar/navigasi.blade.php`: menu "Arsip"

## Fase 4 — Freelancer & Landing
- [x] `app/Http/Controllers/Freelancer/ProjectBrowseController.php`: filter `archive_status=active`
- [x] `routes/web.php` (landing): recentProjects & statistik hanya `archive_status=active`

## Fase 5 — Verifikasi
- [ ] `php -l` semua file
- [ ] `php artisan route:list`
- [ ] `php artisan view:clear`
- [ ] `php artisan view:cache`
- [ ] Cek schema `projects.archive_status`
- [ ] Cek project lama default `active`
- [ ] Cek route tidak konflik
- [ ] Cek project ber-workspace tidak bisa dihapus
