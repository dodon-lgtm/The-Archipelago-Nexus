# TODO - Report V2 (Struktur & Notifikasi)

## Tujuan
Menyempurnakan sistem Report V1 menjadi V2 tanpa mengubah workflow lama yang sudah stabil.
Fokus: Kategori/Tipe Report, notifikasi ke Admin untuk semua report, refactor ke ReportService + Form Request,
kolom handled_by & resolved_at (fondasi untuk fitur Warning/Suspend Account di tahap berikutnya).

## Langkah Implementasi
- [x] 1. Analisis fitur Report V1 (struktur tabel, controller, view, alur admin, bug notifikasi)
- [x] 2. Buat migration baru: tambah kolom category, handled_by, resolved_at pada tabel reports
- [x] 3. Update model Report (konstanta kategori/status, fillable, casts, relasi handledBy)
- [x] 4. Buat ReportService (pemusatan logika store, updateStatus, notifikasi admin)
- [x] 5. Buat Form Request ReportStoreRequest + Admin ReportUpdateStatusRequest
- [x] 6. Refactor ReportController (generic) - pakai service + kategori + notifikasi (perbaiki bug)
- [x] 7. Refactor Company/ReportController - pakai service + notifikasi admin
- [x] 8. Refactor Freelancer/ReportController - pakai service + notifikasi admin
- [x] 9. Refactor Admin/ReportController - pakai service + handled_by + resolved_at
- [x] 10. Tambah dropdown Kategori di form report (reports, company, freelancer)
- [x] 11. Tampilkan kategori di halaman admin (index + show)
- [x] 12. Tampilkan handled_by & resolved_at di halaman admin show
- [x] 13. Verifikasi kompatibilitas V1 (data lama, route lama, workflow) & jalankan migrate

## Catatan
- TIDAK membuat tabel report_histories (dikerjakan bersama fitur Warning/Suspend di tahap berikutnya).
- TIDAK menambahkan tombol Suspend atau placeholder-nya.
- TIDAK menambahkan upload lampiran/bukti maupun prioritas report.
- Kolom baru bersifat nullable/default sehingga kompatibel dengan data & route V1.
- Semua notifikasi report ke admin memakai NotificationService::sendTo().
- Icon notifikasi 'report.created' ditambahkan di nav.blade.php (freelancer/company) & admin.blade.php.
- Migration 2026_08_30_000001 sudah dijalankan (php artisan migrate --force).

---

# Report V2 - Perbaikan Bug (Audit & Fix)

## Tujuan
Memperbaiki 3 bug pada alur Report V2 tanpa migration baru & tanpa merusak jalur yang sudah berjalan.

## Bug yang Ditemukan & Akar Penyebab
- [x] 1. **Bug 1 (Company 403)** - `authorizeStore()` step 4 selalu menolak Company karena form workspace/penawaran ikut mengirim `project_id` milik company sendiri, sehingga `$project->user_id == $userId` selalu true.
- [x] 2. **Bug 2 (Duplikat)** - `store()` tidak punya duplicate guard; langsung `Report::create()` tanpa cek report aktif (menunggu/diproses) yang identik.
- [x] 3. **Bug 3 (Spam notif admin)** - `notifyAdmins()` dipanggil tanpa syarat setiap create, tanpa verifikasi apakah duplikat.

## Implementasi (di `app/Services/ReportService.php`)
- [x] 1. **Fix Bug 1** - Reorder `authorizeStore()`: blok `workspace` dievaluasi paling awal (return), lalu `penawaran`, baru `project`. Blok project HANYA dijalankan untuk laporan proyek murni (tanpa workspace/penawaran). Ditambah guard konsistensi `project_id` terhadap `workspace->project_id` dan `penawaran->project_id`.
- [x] 2. **Fix Bug 2** - `store()` dibungkus `DB::transaction()`. Ditambah `assertNoDuplicate()` yang mengecek kombinasi (reporter, reported_user, project/penawaran/workspace, category) dengan status aktif (`menunggu`/`diproses`). Jika status sudah `selesai`/`ditolak`, user boleh membuat report baru.
- [x] 3. **Fix Bug 3** - `notifyAdmins()` dipanggil HANYA di dalam transaksi SETELAH `Report::create()` berhasil. Jika report ditolak karena duplikat (ValidationException), tidak ada notifikasi baru.

## Audit Jalur yang Tetap Berfungsi
- [x] Report Project (Freelancer → Company)
- [x] Report Workspace (Freelancer → Company & Company → Freelancer)
- [x] Report Penawaran (Company → Freelancer)
- [x] Report Bug Sistem
- [x] Report Generic

---

# Report V2 - Anti-Spam & UX Feedback (Final Fix)

## Tujuan
Menutup celah spam report (Company & Freelancer) dan meningkatkan UX feedback setelah mengirim report,
tanpa migration baru & tanpa merusak workflow Report V2 yang sudah berjalan.

## Akar Penyebab Spam
- [x] Duplicate guard lama memakai `category` sebagai bagian dari kunci unik -> user bisa spam dengan mengganti kategori untuk (reporter, target, konteks) yang sama.
- [x] Menggunakan `orWhere(project_id/penawaran_id/workspace_id)` berpotensi mencampur konteks yang berbeda.

## Implementasi (di `app/Services/ReportService.php` - `assertNoDuplicate()`)
- [x] Duplicate guard kini berdasarkan KONTEKS laporan (bukan kategori):
  - Workspace : reporter + reported_user + workspace_id
  - Penawaran : reporter + reported_user + penawaran_id
  - Project   : reporter + reported_user + project_id
  - Tanpa target/konteks (bug/umum/lainnya) : reporter + subject (case-insensitive)
- [x] Hanya SATU konteks yang dipakai sebagai kunci (prioritas workspace > penawaran > project), tanpa `orWhere` yang mencampur konteks.
- [x] Status aktif yang diblokir: menunggu & diproses. Status selesai/ditolak -> boleh membuat report baru.

## UX Feedback (Semua Role: Company, Freelancer, Generic)
- [x] Controller (Company/Freelancer/Generic) menangkap `ValidationException` dari `ReportService::store()` dan redirect balik dengan error.
- [x] Pesan error duplikat: "Laporan serupa masih dalam proses peninjauan oleh Admin. Anda tidak dapat mengirim laporan yang sama sebelum laporan sebelumnya selesai diproses."
- [x] View create (company/freelancer/generic) menampilkan blok error umum (`$errors->any()`) agar pesan terlihat jelas.
- [x] Pesan sukses diperbarui: "Laporan berhasil dikirim. Terima kasih telah membantu menjaga kualitas platform. Tim Admin akan meninjau laporan Anda secepat mungkin."

## Audit Jalur (Semua memakai ReportService::store() terpusat)
- [x] Report Project (Freelancer → Company)
- [x] Report Company
- [x] Report Workspace (Freelancer → Company & Company → Freelancer)
- [x] Report Penawaran (Company → Freelancer)
- [x] Report Bug Sistem
- [x] Report Generic

## Catatan
- TIDAK ada migration baru.
- TIDAK mengubah NotificationService (notifikasi admin `report.created` tetap berjalan).
- TIDAK menambah duplicate guard di controller (seluruhnya terpusat di ReportService).
- Semua role (Company, Freelancer, Generic) memakai satu guard yang konsisten.

---

# Report V2 - Tambahan: Workspace-Scoped Reporting

## Tujuan
Menambah kemampuan laporan antar-user (Company ↔ Freelancer) secara kontekstual dari halaman **workspace**,
dengan otorisasi & validasi relasi di backend. Report di-link ke workspace agar admin tahu konteks kolaborasi.

## Langkah Implementasi
- [x] 1. Migration baru: tambah kolom `workspace_id` (nullable) pada tabel reports
- [x] 2. Update model Report: tambah `workspace_id` ke fillable + relasi `workspace()`
- [x] 3. Update ReportStoreRequest: validasi `workspace_id` (exists:project_workspaces)
- [x] 4. Update ReportService: `store()` menyimpan workspace_id + `authorizeStore()` validasi otorisasi workspace
- [x] 5. Update Company/ReportController: `create()`/`store()` dukung konteks workspace (company lapor freelancer)
- [x] 6. Update Freelancer/ReportController: `create()`/`store()` dukung konteks workspace (freelancer lapor company)
- [x] 7. Update Admin/ReportController: eager-load relasi workspace di index()/show()
- [x] 8. Update views admin (index + show): tampilkan workspace terkait + role target
- [x] 9. Update views company & freelancer (create + index): konteks workspace + hidden inputs + kategori
- [x] 10. Tambah tombol "Laporkan" di workspace/show.blade.php (kontekstual per role)
- [x] 11. Verifikasi syntax PHP & route (artisan route:list) & migration (migrate:status)

## Aturan Otorisasi (ReportService::authorizeStore)
- User tidak bisa melaporkan dirinya sendiri.
- Laporan terhadap user wajib punya konteks (project_id / penawaran_id / workspace_id).
- Kategori non-target (bug-sistem, umum, lainnya) tidak boleh mengisi reported_user_id.
- Konteks project: pelapor harus freelancer, target harus owner proyek.
- Konteks penawaran: pelapor harus company pemilik proyek, target harus freelancer pembuat penawaran.
- Konteks workspace: pelapor harus company ATAU freelancer pada workspace tsb, target harus pihak lawan.
- Semua ID dari request diverifikasi ulang di backend (tidak dipercaya langsung dari URL).

## Catatan
- TIDAK menambahkan kolom reported_role (role target diambil dari relasi reportedUser->role, sesuai arahan).
- TIDAK menjadikan 'company'/'freelancer' sebagai kategori laporan (kategori hanya berbasis masalah).
- TIDAK merombak/membuat halaman profil Company view-only untuk fitur ini (dapat dikerjakan tahap terpisah).
- Migration 2026_08_31_000001 sudah dijalankan (php artisan migrate --force).
