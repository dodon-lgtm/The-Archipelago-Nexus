# TODO - Sistem Notifikasi

## ✅ = Selesai, ⬜ = Belum, 🔄 = Sedang Dikerjakan

### TAHAP 1: Database & Migration
- [✅] Buat migration `2026_08_20_000001_add_columns_to_notifications_table.php`
  - Kolom: sender_id, type, workspace_id, project_id, payment_id, company_account_request_id, data (JSON), read_at
  - Index: [user_id, is_read], type, created_at
- [✅] Jalankan migration

### TAHAP 2: Model Notification
- [✅] Update model dengan fillable baru (sender_id, type, workspace_id, project_id, payment_id, company_account_request_id, data, read_at)
- [✅] Casts: data => array, read_at => datetime
- [✅] Scopes: unread(), forUser(), ofType()
- [✅] Relasi: sender(), workspace(), project(), payment(), companyAccountRequest()

### TAHAP 3: NotificationService
- [✅] Buat `app/Services/NotificationService.php`
- [✅] Method `send(array $data): ?Notification`
- [✅] Method `sendTo()` dengan named arguments

### TAHAP 4: Integrasi Controller
- [✅] `Admin/PaymentController@verify` — notif ke freelancer & company (payment.verified)
- [✅] `Admin/PaymentController@reject` — notif ke freelancer & company (payment.rejected)
- [✅] `Company/PaymentController@uploadProof` — notif ke admin (payment.waiting)
- [✅] `Company/ProjectController@selectFreelancer` — notif accepted + rejected (offer.accepted, offer.rejected)
- [✅] `Freelancer/ProjectBrowseController@store` — notif ke company (offer.sent)
- [✅] `ProjectSubmissionController@store` — notif ke company (submission.uploaded)
- [✅] `ProjectSubmissionController@accept` — notif ke freelancer (submission.accepted)
- [✅] `ProjectSubmissionController@requestRevision` — notif ke freelancer (submission.revision_requested)
- [✅] `WorkspaceController@sendMessage` — notif ke lawan bicara (workspace.message)
- [✅] `CompanyAccountRequestController@store` — notif ke admin (company_request.created)

### TAHAP 5: Frontend
- [✅] Update `navbar/nav.blade.php` — polling 60 detik, icon dinamis per type, redirect dari data.redirect
- [✅] Update `layouts/admin.blade.php` — dropdown notifikasi admin lengkap

### VERIFIKASI (lakukan setelah semua tahap):
- [⬜] Login sebagai Freelancer → klik ikon lonceng → lihat notifikasi
- [⬜] Login sebagai Company → klik ikon lonceng → lihat notifikasi
- [⬜] Login sebagai Admin → klik ikon lonceng → lihat notifikasi
- [⬜] Notifikasi muncul secara realtime (polling 60 detik)
- [⬜] Klik notifikasi → redirect ke halaman yang sesuai
- [⬜] Tombol "Tandai semua sudah dibaca" berfungsi
- [⬜] Badge jumlah notifikasi tidak terbaca berubah secara otomatis

