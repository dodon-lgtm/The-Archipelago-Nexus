APEXFORGE LABS PROJECT HISTORY, CURRENT STATE, IMPLEMENTED FEATURES,
TOOLS, AND ROADMAP
============================================================

Dokumen ini adalah handoff/context file untuk melanjutkan pengembangan
proyek ApexForge Labs tanpa kehilangan arah dari pekerjaan sebelumnya.

============================================================ 1.
IDENTITAS PROYEK
============================================================

Nama proyek: ApexForge Labs

Riwayat nama proyek: 1. FreelanceID 2. The Archipelago Nexus 3.
ApexForge Labs (nama saat ini)

Jenis proyek: Aplikasi freelance marketplace Indonesia berbasis Laravel.

Konsep utama: - Company membuat dan mengelola proyek. - Freelancer dapat
melihat proyek dan mengirim penawaran. - Company memilih freelancer. -
Setelah freelancer diterima, dibuat Workspace. - Workspace menjadi pusat
proses pengerjaan proyek. - Sistem memiliki pembayaran, tahapan
pengerjaan, submission, review, laporan/dispute, notifikasi, dan
administrasi. - Sistem memiliki role user/freelancer/company/admin.

============================================================ 2. TUJUAN
UTAMA PROYEK
============================================================

ApexForge Labs ditujukan sebagai platform freelance Indonesia yang
menghubungkan Company dengan Freelancer.

Alur besar: Landing Page ↓ Register / Login ↓ Freelancer / Company ↓
Project ↓ Penawaran ↓ Negosiasi / Pemilihan Freelancer ↓ Workspace ↓
Pembayaran ↓ Pengerjaan ↓ Tahapan / Progress ↓ Submission hasil ↓ Review
Company ↓ Revisi atau Selesai ↓ Pendapatan Freelancer / Pengeluaran
Company ↓ Audit / Report / Dispute jika terjadi masalah

============================================================ 3.
TEKNOLOGI DAN TOOLS YANG DIGUNAKAN
============================================================

Backend: - PHP - Laravel 12.x - Laravel Eloquent - Laravel migrations -
Laravel controllers - Laravel middleware - Laravel Form Request -
Laravel Blade - Laravel routes - Laravel Tinker

Database: - MySQL

Local development: - Laragon - PHP CLI - php artisan - php -S
127.0.0.1:8000 -t public

Frontend: - Blade - HTML - CSS - JavaScript - Bootstrap pada beberapa
bagian UI

Dependency management: - Composer

Version control: - Git - GitHub

Payment: - Sistem pembayaran manual - Midtrans direncanakan/dalam proses
integrasi - PayPal pernah diuji tetapi kemudian ditinggalkan

Debugging: - Browser DevTools / F12 - Network tab - Laravel log -
terminal - git status - git log - git grep - php artisan
migrate:status - composer show - Composer autoload - Laravel Tinker

============================================================ 4. RIWAYAT
PEMBAYARAN ============================================================

Sebelumnya sistem pembayaran menggunakan pembayaran manual.

Metode manual yang dipertahankan: - Transfer Bank - QRIS - E-Wallet -
Upload bukti pembayaran - Verifikasi Admin

Pernah diimplementasikan PayPal Sandbox.

Masalah PayPal: - PayPal membutuhkan konfigurasi credential. - Pernah
muncul error cURL certificate: cURL error 77 error setting certificate
file - Setelah konfigurasi certificate diperbaiki, OAuth token berhasil
diperoleh. - PayPal kemudian berhasil membuka halaman login/payment
Sandbox. - Namun kebutuhan proyek berubah: PayPal tidak digunakan lagi
karena diminta menggunakan payment gateway lokal Indonesia yang lebih
mudah.

Keputusan: PAYPAL TIDAK DIGUNAKAN LAGI.

Migration PayPal lama TIDAK DIHAPUS karena sudah pernah dijalankan dan
merupakan histori database.

Migration:
database/migrations/2026_08_13_130632_add_paypal_columns_to_payments_table.php

Kolom historis: - paypal_order_id - paypal_capture_id -
paypal_payer_id - paypal_payer_email

Hasil pencarian terakhir: git grep -in “paypal” – app config routes
resources database

Hanya ditemukan referensi PayPal pada migration historis tersebut.
Referensi PayPal di composer/vendor README dianggap bukan bagian
aplikasi.

============================================================ 5. PAYMENT
DATABASE ============================================================

Migration core:
database/migrations/2026_08_10_000002_create_payments_table.php

Migration tersebut sudah dijalankan dan TIDAK BOLEH diubah secara
destruktif.

Payment memiliki konsep data: - workspace_id - company_id -
freelancer_id - invoice_number - amount - platform_fee -
freelancer_receive - payment_method - payment_proof - status -
verifier/admin-related data

Pernah terjadi masalah: Model Payment sempat tertimpa sehingga beberapa
bagian hilang. Hal tersebut menyebabkan error database: Field
‘company_id’ doesn’t have a default value

Masalah kemudian diketahui berasal dari model Payment yang tertimpa.

============================================================ 6. MIDTRANS
FOUNDATION YANG SUDAH DITERAPKAN
============================================================

Midtrans dipilih sebagai pengganti PayPal.

Package: midtrans/midtrans-php

Versi yang berhasil dipasang: 2.6.2

Foundation sudah selesai dan sudah di-push ke Git.

File/config yang sudah diterapkan: - composer.json - composer.lock -
config/services.php - .env.example - app/Models/Payment.php -
app/Services/MidtransService.php -
database/migrations/2026_08_15_102928_add_midtrans_columns_to_payments_table.php

Konfigurasi: MIDTRANS_SERVER_KEY MIDTRANS_CLIENT_KEY
MIDTRANS_IS_PRODUCTION

Service: app/Services/MidtransService.php

Method yang dirancang/tersedia: - configure() - createSnapToken() -
verifyNotification() - getTransactionStatus()

Kolom Midtrans: - midtrans_transaction_id - midtrans_payment_type -
midtrans_response

Payment model: - fillable sudah mendukung field Midtrans -
midtrans_response sudah dicast sebagai array

Testing foundation: - Composer autoload: PASS - Laravel config: PASS -
Migration: PASS - Midtrans SDK loading: PASS - Tinker configuration:
PASS

Foundation Midtrans: SELESAI / SUDAH DI-PUSH

============================================================ 7. CURRENT
MIDTRANS TARGET FLOW
============================================================

Target flow:

Company menerima freelancer ↓ Workspace dibuat ↓ Workspace menunggu
pembayaran ↓ Company membuka halaman pembayaran ↓ Company memilih “Bayar
dengan Midtrans” ↓ Backend mengambil Payment yang sah ↓ Backend
mengambil amount dari database ↓ Backend membuat transaksi Midtrans ↓
Backend mendapatkan Snap Token ↓ Frontend membuka Midtrans Snap ↓
Company melakukan pembayaran ↓ Midtrans memproses pembayaran ↓ Midtrans
mengirim notification ke backend ↓ Backend memverifikasi notification ↓
Payment diperbarui ↓ Jika settlement/capture berhasil: Payment = paid ↓
Workspace masuk tahap pengerjaan

PENTING: Payment berhasil TIDAK sama dengan proyek selesai.

Setelah pembayaran berhasil, Workspace harus masuk ke proses pengerjaan,
bukan langsung menjadi Selesai.

============================================================ 8. MIDTRANS
YANG MASIH DIRANCANG / BELUM SELESAI
============================================================

Phase berikutnya:

PHASE 3: Create Snap Token Backend

Tujuan: - Company dapat meminta Snap Token untuk Payment yang valid. -
Authorization harus memeriksa Company pemilik Workspace. - Payment harus
berasal dari database. - Amount harus berasal dari database. -
Invoice/order ID tidak boleh dipercaya dari frontend. - Backend
mengembalikan Snap Token. - Payment belum boleh menjadi paid hanya
karena Snap Token berhasil dibuat.

PHASE 4: Frontend Midtrans Snap - Tombol Bayar dengan Midtrans -
Midtrans Snap JS - Client Key - Popup/redirect pembayaran - callback
success/pending/error/close

Callback frontend tidak boleh dianggap sebagai bukti pembayaran final.

PHASE 5: Midtrans Notification / Webhook - Endpoint notification -
Signature verification - Cari Payment berdasarkan order ID - Cocokkan
nominal - Update status Payment - Idempotency - Logging -
Server-to-server verification

PHASE 6: Integrasi status pembayaran dengan Workspace - Payment success
→ Workspace masuk pengerjaan - Payment pending → tetap menunggu -
Payment failed/expired/cancel → tidak boleh lanjut pengerjaan - Payment
success tidak berarti project selesai

PHASE 7: Security dan testing - Authorization - Amount integrity -
Signature verification - Duplicate webhook - Manipulasi frontend - Order
ID manipulation - Company mengakses Workspace Company lain

PHASE 8: Regression test pembayaran manual - Transfer Bank - QRIS -
E-Wallet - Upload bukti - Admin verification

============================================================ 9. PAYMENT
POLICY / WORKSPACE POLICY
============================================================

Keputusan bisnis yang sudah disepakati:

Company membayar terlebih dahulu sebelum pengerjaan dilanjutkan.

Setelah pembayaran: - Freelancer mengerjakan proyek. - Freelancer
mengirim hasil/source code sesuai ketentuan. - Company melakukan
review. - Company dapat menyelesaikan atau meminta revisi.

Jika Company diam/tidak menyelesaikan proses: - Transaksi tidak boleh
dibiarkan tanpa mekanisme penyelesaian. - Jika ada masalah, user dapat
melapor ke Admin. - Admin dapat mengaudit kondisi dan menentukan pihak
yang memenuhi atau melanggar persyaratan. - Sistem laporan/dispute harus
dapat menjadi jalur penyelesaian.

Jika Freelancer sudah menyelesaikan pekerjaan dan memberikan source code
tetapi Company tidak menekan selesai: - Tidak boleh otomatis menganggap
Freelancer salah. - Kondisi harus dapat diaudit. - Admin dapat memeriksa
bukti, progress, submission, komunikasi, dan status pembayaran. -
Keputusan pencairan/penyelesaian harus mengikuti aturan sistem yang
nantinya diterapkan.

============================================================ 10. REVISI
PROYEK YANG DIMINTA
============================================================

Daftar revisi besar yang diberikan:

PAYMENT - Tambah payment gateway - Awalnya PayPal, kemudian diganti ke
gateway lokal Indonesia - Sekarang menggunakan rencana Midtrans -
Company membayar sebelum proyek dilanjutkan - Tarik saldo harus bisa -
Pendapatan freelancer harus terlihat - Pengeluaran company harus
terlihat - Invoice dapat dicetak satuan atau semua - Halaman pendapatan
freelancer dapat mencetak invoice

PROJECT - Edit project harus sempurna - Project dapat diubah status
menjadi tutup - Tombol project memiliki fungsi masukkan ke arsip - Semua
project dan detailnya ditampilkan di landing page - Halaman Project Saya
pada Company diminimalkan - Freelancer melihat rekomendasi pekerjaan
terbaru dengan status open, bukan pekerjaan yang sudah selesai

WORKSPACE - Workspace awal jangan langsung 100% - Company dapat menambah
tahap pengerjaan - Tahapan/progress harus mendukung workflow yang lebih
realistis

NEGOTIATION - Tambah negosiasi penawaran proyek

COMPANY - Company dapat menghubungi Admin - Company dapat melihat
pengeluaran - Company dapat melihat pengeluaran dari project - Company
dapat menambah tahap pengerjaan

FREELANCER - Freelancer dapat melihat pendapatan - Detail pendapatan
memiliki top pendapatan - Freelancer dapat melihat pendapatan dari
setiap project

ADMIN - Filter dashboard admin: - bulan - tahun - total - semua data -
Tambah keterangan pendapatan untuk Admin - Admin dapat mengontrol
masalah/dispute - Admin dapat melakukan audit terhadap kasus
pembayaran/proyek

ACCOUNT / AUTH - User tidak boleh menjadi Admin melalui mekanisme
biasa - Lupa password belum ada - Lupa password diinginkan berupa form
laporan ke Admin yang kemudian dapat menghubungi user lewat email -
Tambah verifikasi email saat register - Jika user sudah login, landing
page harus tetap berfungsi sesuai status login - Sebelum tombol Daftar
Sekarang harus ada kebijakan dan privasi

PREMIUM - Upgrade popup - Upgrade popup Premium Pro

HELP - Pusat bantuan dapat diakses publik

FOOTER - Footer hanya ada di landing page - Semua link/footer harus
berfungsi

MOBILE - Optimalisasi Mobile UI - Sidebar ketika ditutup harus tetap
menampilkan logo

SERVICE - Respon pelayanan 09:00 - 17:00 WIB

============================================================ 11. AUDIT /
DISPUTE ============================================================

Audit/dispute sudah direncanakan sebagai bagian penting sistem.

Prinsip: - Payment - Workspace - Submission - Review - Report/Dispute -
Earnings

harus tetap memiliki state masing-masing.

Admin dapat digunakan untuk: - memeriksa laporan - melihat bukti -
melihat histori - menentukan pihak yang bermasalah - menangani kasus
pembayaran/proyek

Jangan mencampurkan Payment = Project Completed.

============================================================ 12.
DATABASE / MIGRATION STATUS
============================================================

Migration penting yang sudah dijalankan mencakup:

-   users
-   cache
-   jobs
-   company_account_requests
-   categories
-   projects
-   role users
-   penawarans
-   freelancer_profiles
-   company_profiles
-   saved_projects
-   notifications
-   reviews
-   selected_at pada penawarans
-   project_workspaces
-   messages
-   progress_histories
-   project_submissions
-   submission_files
-   reports
-   payment status pada workspace
-   payments
-   PayPal historical columns
-   Midtrans columns
-   berbagai migration lanjutan
    notifications/reviews/reports/workspace/project

Migration PayPal:
2026_08_13_130632_add_paypal_columns_to_payments_table.php Status: Ran
Keputusan: jangan dihapus/rollback karena merupakan histori.

Migration Midtrans:
2026_08_15_102928_add_midtrans_columns_to_payments_table.php Status: Ran

============================================================ 13. GIT /
VERSION CONTROL
============================================================

Git digunakan untuk checkpoint dan recovery.

Commit penting yang tercatat: d024e8f - bug pembayaran fixed e8c9672 -
Test UI cc6c432 - upgrade UI e428747 - ui update fbb3a23 - pembaharuan
report v3 88% b4400b2 - penyempurnaan fitur v1.0.2 dc9658d - Merge
branch main 841e1ae - menyempurnakan ux

Pada tahap rollback sebelumnya ditemukan bahwa branch pernah kembali ke:
d024e8f bug pembayaran fixed

Setelah itu PayPal dan Midtrans foundation dikerjakan.

Checkpoint terakhir: Midtrans backend foundation sudah di-push.

Prinsip Git: - Selalu cek git status sebelum perubahan. - Cek git diff
sebelum commit. - Jangan menggunakan git reset –hard sembarangan. -
Jangan menggunakan git clean -fd sembarangan. - Jangan menghapus
perubahan user yang belum di-commit. - Commit setiap fase besar sebagai
checkpoint. - Push setelah checkpoint dianggap stabil.

============================================================ 14. MASALAH
/ INSIDEN YANG PERNAH TERJADI
============================================================

1.  Laravel artisan serve error

Muncul error: Failed opening required:
vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php

Project menggunakan Laravel 12.63.0.

Solusi yang dipilih: menggunakan:

php -S 127.0.0.1:8000 -t public

Karena berjalan normal dan tidak ingin menghabiskan waktu memperbaiki
mekanisme artisan serve.

2.  PayPal cURL error 77

Error: cURL error 77: error setting certificate file

Certificate: D:.pem

Setelah pengecekan: curl.cainfo menunjuk ke certificate. Tinker kemudian
berhasil memperoleh OAuth token PayPal.

PayPal akhirnya bisa dibuka, tetapi diputuskan untuk tidak digunakan.

3.  Payment company_id error

Error: Field ‘company_id’ doesn’t have a default value

Penyebab: Model Payment sempat tertimpa/hilang sebagian.

Model kemudian diperbaiki.

4.  PayPal references

Pencarian terakhir pada source aplikasi: git grep -in “paypal” – app
config routes resources database

Hasil: hanya migration PayPal lama.

5.  PSR-4 warning

Composer pernah menunjukkan: Class Applocated in
./app/Http/Controllers/review/ReviewController.php does not comply with
PSR-4 autoloading standard.

Ini perlu diperhatikan jika masih muncul.

============================================================ 15.
PROMPT.MD ============================================================

PROMPT.md bukan tempat menyimpan prompt implementasi.

Fungsi PROMPT.md: - menjelaskan seluk-beluk proyek - nama dan sejarah
proyek - arsitektur - aturan penting - fitur yang sudah ada - fitur yang
sedang dikerjakan - fitur yang direncanakan - status progress -
informasi penting untuk AI yang melanjutkan proyek

Prompt pengerjaan sebaiknya diberikan langsung dalam chat.

Jika PROMPT.md diperbarui: - hanya masukkan fakta aktual - jangan
menyalin seluruh prompt pengerjaan - jangan memasukkan instruksi phase
yang sedang dijalankan secara mentah

============================================================ 16. CURRENT
STATUS ============================================================

Status saat dokumen ini dibuat:

PROJECT: ApexForge Labs

Backend Laravel: AKTIF

Database: AKTIF

Git: AKTIF

Manual Payment: SUDAH ADA / HARUS DIPERTAHANKAN

PayPal: DITINGGALKAN HANYA MIGRATION HISTORIS

Midtrans Foundation: SELESAI SUDAH DI-PUSH

Midtrans Snap Token: BELUM SELESAI

Midtrans Frontend Snap: BELUM SELESAI

Midtrans Webhook: BELUM SELESAI

Automatic Payment Verification: BELUM SELESAI

Payment-Workspace final integration: BELUM SELESAI

Audit/Dispute: SUDAH DIRANCANG IMPLEMENTASI LANJUTAN MASIH DIBUTUHKAN

Withdrawal: MASIH DIRENCANAKAN

Invoice Printing: MASIH DIRENCANAKAN

Email Verification: MASIH DIRENCANAKAN

Forgot Password: MASIH DIRENCANAKAN

Admin Dashboard Filtering: MASIH DIRENCANAKAN

Mobile Optimization: MASIH DIRENCANAKAN

Premium Pro: MASIH DIRENCANAKAN

Public Help Center: MASIH DIRENCANAKAN

Project Archive: MASIH DIRENCANAKAN / SEBAGIAN STATUS ARCHIVE SUDAH ADA

Project Close Status: MASIH PERLU DISEMPURNAKAN

Negotiation: MASIH DIRENCANAKAN

Company-Admin Contact: MASIH DIRENCANAKAN

============================================================ 17. NEXT
IMMEDIATE STEP
============================================================

NEXT:

PHASE 3 — MIDTRANS SNAP TOKEN BACKEND

Fokus: - audit kode aktual - endpoint create transaction - authorization
Company - validasi Payment - amount dari database - invoice dari
database - create Snap Token melalui MidtransService - simpan tracking
transaction jika tersedia - response JSON Snap Token - testing backend

Setelah itu:

PHASE 4 — MIDTRANS SNAP FRONTEND

Kemudian:

PHASE 5 — MIDTRANS WEBHOOK

Kemudian:

PHASE 6 — PAYMENT + WORKSPACE STATE

Kemudian:

PHASE 7 — SECURITY / IDEMPOTENCY

Kemudian:

PHASE 8 — REGRESSION TEST MANUAL PAYMENT

============================================================ 18. ATURAN
PENTING UNTUK AI BERIKUTNYA
============================================================

1.  Gunakan kode aktual sebagai sumber kebenaran.
2.  Jangan menebak nama field, route, model, atau status.
3.  Audit sebelum coding.
4.  Kerjakan satu phase pada satu waktu.
5.  Jangan melanjutkan phase berikutnya tanpa instruksi.
6.  Jangan menghapus migration historis PayPal.
7.  Jangan menghapus pembayaran manual.
8.  Jangan mengekspos Midtrans Server Key.
9.  Jangan mempercayai callback frontend sebagai bukti pembayaran final.
10. Amount pembayaran harus berasal dari database.
11. Payment paid tidak berarti project selesai.
12. Gunakan webhook/server-to-server verification untuk status final.
13. Pastikan webhook idempotent.
14. Jangan melakukan destructive Git command tanpa izin.
15. Jangan melakukan commit/push otomatis kecuali diminta.
16. Jangan mengarang hasil testing.
17. Jika struktur proyek berbeda dari rencana, jelaskan dan ikuti kode
    aktual.
18. Jangan mengubah file yang tidak berhubungan dengan phase.
19. PROMPT.md adalah dokumentasi proyek, bukan tempat menyimpan prompt
    pengerjaan.
20. Nama proyek saat ini adalah APEXFORGE LABS.

============================================================ 19.
CHECKPOINT ============================================================

CHECKPOINT TERAKHIR:

Midtrans Backend Foundation STATUS: SELESAI STATUS GIT: SUDAH DI-PUSH

NEXT CHECKPOINT:

Midtrans Snap Token Backend STATUS: BELUM DIKERJAKAN

END OF PROJECT HANDOFF DOCUMENT
