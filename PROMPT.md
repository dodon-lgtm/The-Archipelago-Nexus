Struktur : 
PROMPT.md
│
├── 1. Identitas Project
├── 2. Aturan Mutlak AI
├── 3. Kondisi Project Saat Ini
├── 4. Requirement Revisi
├── 5. Payment Gateway Midtrans
├── 6. Payment Flow
├── 7. Workspace Flow
├── 8. Dispute System
├── 9. Saldo Freelancer
├── 10. Withdrawal
├── 11. Admin Control
├── 12. Authorization & Security
├── 13. Database Rules
├── 14. PayPal Removal
├── 15. Audit Procedure
├── 16. Implementation Procedure
├── 17. Testing Procedure
├── 18. Git Safety
└── 19. Final Report Format


# THE ARCHIPELAGO NEXUS

## MASTER AI DEVELOPMENT INSTRUCTION

> **Dokumen ini adalah instruksi utama untuk AI yang membantu mengembangkan project The Archipelago Nexus.**
>
> AI WAJIB membaca seluruh file `PROMPT.md` sebelum melakukan audit, perubahan kode, refactor, migration, atau implementasi fitur.

---

# 1. IDENTITAS PROJECT

Nama project:

**The Archipelago Nexus**

Jenis aplikasi:

**Marketplace Freelance Indonesia**

Teknologi utama:

* Laravel 12
* PHP 8.2+
* MySQL
* Blade
* JavaScript
* Bootstrap/CSS sesuai struktur project yang sudah ada
* Midtrans sebagai payment gateway lokal Indonesia
* Git/GitHub

Project memiliki minimal tiga role utama:

* `freelancer`
* `company`
* `admin`

---

# 2. ATURAN MUTLAK

## 2.1 Jangan mengarang struktur project

AI DILARANG mengasumsikan bahwa:

* controller tertentu sudah ada;
* model tertentu sudah ada;
* migration tertentu sudah ada;
* route tertentu sudah ada;
* field database tertentu sudah ada;
* service tertentu sudah ada;
* middleware tertentu sudah ada.

Jika belum ditemukan pada source code:

**Katakan bahwa informasi tersebut tidak ditemukan.**

Jangan membuat asumsi.

---

## 2.2 Baca source code sebelum mengubahnya

Sebelum mengubah sebuah file:

1. Baca file tersebut.
2. Pahami fungsi yang sudah ada.
3. Cari dependency-nya.
4. Cari route yang memanggilnya.
5. Cari model/database yang digunakan.
6. Cari view/frontend yang bergantung kepadanya.
7. Baru tentukan perubahan.

Jangan mengganti seluruh file hanya karena ada satu bug.

---

## 2.3 Jangan melakukan perubahan besar tanpa izin

Jika user hanya meminta:

> "audit"

Maka:

* JANGAN mengubah kode.
* JANGAN membuat migration.
* JANGAN mengubah database.
* JANGAN menghapus file.
* JANGAN melakukan refactor.

Jika user meminta implementasi, lakukan perubahan secara bertahap.

---

# 3. WORKFLOW WAJIB AI

Gunakan tahapan berikut:

```text
READ
 ↓
UNDERSTAND
 ↓
AUDIT
 ↓
REPORT
 ↓
WAIT FOR APPROVAL
 ↓
IMPLEMENT
 ↓
TEST
 ↓
REPORT
```

Jangan melewati tahap audit jika perubahan berpotensi memengaruhi database, authentication, payment, saldo, atau authorization.

---

# 4. KONDISI PROJECT

Project sebelumnya menggunakan PayPal Sandbox.

Payment gateway tersebut sekarang:

**SUDAH TIDAK DIGUNAKAN.**

Payment gateway yang digunakan selanjutnya:

**MIDTRANS**

Fokus pembayaran:

**Indonesia / IDR**

Jangan menggunakan USD untuk payment flow baru.

---

# 5. PAYPAL DEPRECATION

Semua kode aplikasi yang berkaitan dengan PayPal dianggap obsolete.

Cari menggunakan:

```bash
git grep -in "paypal"
```

Namun jangan salah mengartikan hasil pencarian.

Jika ditemukan URL PayPal di:

```text
composer.lock
```

yang hanya merupakan metadata/donation URL dependency pihak ketiga, jangan menghapus atau mengubahnya.

Yang harus diaudit adalah kode aplikasi.

Contoh yang harus dianggap obsolete:

```text
PayPalService
paypal_order_id
paypal_capture_id
paypal_payer_id
paypal_payer_email
PAYPAL_CLIENT_ID
PAYPAL_CLIENT_SECRET
PayPal API
PayPal webhook
PayPal Smart Buttons
route PayPal
PayPal JavaScript SDK
```

Sebelum menghapus sesuatu, pastikan file/field tersebut memang hanya digunakan oleh PayPal.

---

# 6. MIDTRANS

Payment gateway baru:

**Midtrans**

Tujuan:

* Company membayar invoice.
* Payment diproses oleh Midtrans.
* Backend menerima notification dari Midtrans.
* Backend memverifikasi status transaksi.
* Payment tidak boleh dianggap berhasil hanya karena frontend menerima callback.

---

# 7. PAYMENT SECURITY

JANGAN pernah mempercayai:

* amount dari frontend;
* invoice dari frontend;
* workspace_id dari frontend;
* company_id dari frontend;
* status pembayaran dari frontend.

Backend harus mengambil data transaksi dari database dan melakukan authorization.

Nominal pembayaran harus berasal dari database.

Contoh:

```text
Frontend:
"Bayar Rp 300.000"

TIDAK BOLEH langsung dipercaya.

Backend:
Payment ID
 ↓
Database
 ↓
workspace
 ↓
company
 ↓
amount
 ↓
invoice
 ↓
Midtrans
```

---

# 8. PAYMENT FLOW

Target flow:

```text
Company menerima Freelancer
        ↓
Workspace dibuat
        ↓
Menunggu Pembayaran
        ↓
Company membuka invoice
        ↓
Company membayar melalui Midtrans
        ↓
Midtrans memproses pembayaran
        ↓
Notification masuk ke backend
        ↓
Backend memverifikasi transaksi
        ↓
Payment = paid
        ↓
Workspace = Dalam Pengerjaan
```

Pembayaran berhasil TIDAK otomatis berarti proyek selesai.

---

# 9. WORKSPACE FLOW

Target workflow:

```text
Menunggu Pembayaran
        ↓
Dalam Pengerjaan
        ↓
Menunggu Review Company
        ↓
    ┌───┴────┐
    ↓        ↓
Terima     Revisi
    ↓        ↓
 Selesai   Pengerjaan
             ↓
       Menunggu Review
```

Status aktual project harus diaudit terlebih dahulu.

Jangan menambahkan status baru sebelum memeriksa enum/status yang sudah ada.

---

# 10. SUBMISSION

Freelancer harus dapat mengirim:

* hasil pekerjaan;
* file;
* source code;
* catatan;
* informasi pengerjaan.

Setelah dikirim:

```text
Workspace
↓
Menunggu Review Company
```

Company kemudian dapat:

### TERIMA

```text
Submission diterima
↓
Workspace Selesai
↓
Dana dapat diproses sesuai settlement system
```

### REVISI

```text
Company meminta revisi
↓
Workspace kembali ke tahap pengerjaan
↓
Freelancer melakukan revisi
```

---

# 11. COMPANY TIDAK MERESPONS

JANGAN langsung:

* refund otomatis;
* memberikan uang ke freelancer;
* menganggap proyek selesai.

Jika Company tidak merespons dalam batas waktu:

```text
Menunggu Review
        ↓
Deadline review terlewati
        ↓
Freelancer dapat melapor
        ↓
Admin Review
```

Admin menentukan penyelesaian berdasarkan bukti.

---

# 12. DISPUTE SYSTEM

Dispute dapat berasal dari:

### Freelancer

Contoh:

> Company tidak memberikan konfirmasi setelah pekerjaan selesai.

### Company

Contoh:

> Freelancer tidak memenuhi requirement.

---

# 13. ADMIN DISPUTE REVIEW

Admin harus dapat melihat:

* proyek;
* workspace;
* company;
* freelancer;
* payment;
* invoice;
* nominal;
* timeline;
* progress;
* submission;
* file;
* source code jika tersedia;
* pesan;
* revisi;
* laporan;
* bukti;
* aktivitas terakhir.

Admin kemudian menentukan keputusan.

---

# 14. KEMUNGKINAN HASIL DISPUTE

Contoh:

### Freelancer benar

```text
Admin
 ↓
Freelancer memenuhi requirement
 ↓
Dana dapat dilepas
```

### Company benar

```text
Admin
 ↓
Freelancer tidak memenuhi requirement
 ↓
Penyelesaian/refund sesuai aturan
```

### Kedua pihak bermasalah

```text
Admin
 ↓
Evaluasi bukti
 ↓
Keputusan khusus
```

Jangan membuat aturan otomatis tanpa persetujuan user.

---

# 15. SALDO FREELANCER

Saldo freelancer tidak boleh hanya berupa:

```php
$user->balance += $amount;
```

tanpa audit trail.

Sistem sebaiknya memiliki catatan transaksi.

Contoh:

```text
Payment
 ↓
Settlement
 ↓
Earning Transaction
 ↓
Freelancer Balance
```

Setiap perubahan saldo harus dapat dilacak.

Minimal informasi transaksi:

* user;
* amount;
* type;
* reference;
* workspace;
* payment;
* status;
* created_at.

---

# 16. WITHDRAWAL

Freelancer nantinya dapat melakukan:

**Tarik Saldo**

Flow:

```text
Freelancer
 ↓
Tarik Saldo
 ↓
Validasi saldo
 ↓
Withdrawal dibuat
 ↓
Admin/Payment System memproses
 ↓
Withdrawal selesai
 ↓
Saldo diperbarui
```

Jangan mengurangi saldo sebelum transaksi withdrawal tervalidasi sesuai desain sistem.

---

# 17. COMPANY EXPENSE

Company harus dapat melihat:

* total pengeluaran;
* pengeluaran per proyek;
* invoice;
* payment;
* status payment;
* tanggal pembayaran.

---

# 18. FREELANCER INCOME

Freelancer harus dapat melihat:

* total pendapatan;
* pendapatan per proyek;
* invoice;
* payment;
* status;
* tanggal;
* transaction history.

---

# 19. INVOICE

Invoice harus dapat:

* dilihat;
* dicetak;
* dicetak satuan;
* dicetak seluruhnya jika fitur tersebut tersedia.

Freelancer juga dapat mencetak invoice/riwayat pendapatan jika memang desain sistem mendukungnya.

Jangan membuat route baru sebelum memeriksa route invoice yang sudah ada.

---

# 20. PROJECT LIMIT COMPANY

Requirement:

Company gratis dapat membuat:

**3 proyek**

Setelah batas tercapai:

```text
Project #1
Project #2
Project #3
        ↓
Batas tercapai
        ↓
Project berikutnya membutuhkan pembayaran/upgrade
```

Audit dahulu sistem project creation sebelum implementasi.

Jangan hanya menyembunyikan tombol.

Validasi harus berada di backend.

---

# 21. PROJECT STATUS

Project harus dapat diubah menjadi:

**Tutup**

Tujuannya agar user mengetahui proyek sudah tidak menerima aktivitas baru.

Periksa:

* model;
* migration;
* controller;
* policy;
* route;
* view;
* query listing.

Jangan hanya mengubah UI.

---

# 22. ARCHIVE

Project yang ditutup dapat memiliki mekanisme:

**Masuk Arsip**

Audit dahulu apakah project sudah memiliki field archive/status.

Jika sudah tersedia, gunakan struktur tersebut.

Jangan membuat kolom duplikat.

---

# 23. ADMIN

Admin harus memiliki kontrol terhadap:

* users;
* company;
* freelancer;
* projects;
* categories;
* offers;
* workspace;
* payments;
* reports;
* dispute;
* earnings;
* withdrawals;
* notifications.

Namun jangan menambahkan fitur yang belum diperlukan tanpa persetujuan.

---

# 24. ROLE SECURITY

User TIDAK BOLEH menjadi admin hanya dengan:

```http
POST /register
role=admin
```

Backend harus mengabaikan atau menolak role admin dari user input.

Role admin harus ditentukan melalui mekanisme server-side yang aman.

Audit:

* RegisterController
* validation
* middleware
* policies
* gates
* routes

---

# 25. LOGIN

Jika user sudah login:

Landing page harus tetap berfungsi.

Audit:

* navbar;
* login state;
* dashboard link;
* logout;
* role-based navigation.

Jangan membuat user yang sudah login terjebak di landing page.

---

# 26. FORGOT PASSWORD

Requirement:

Jika user memilih:

**Lupa Password**

Maka tersedia mekanisme bantuan.

Requirement user:

> Form laporan kepada admin dan admin dapat menghubungi user melalui email.

Namun sebelum implementasi:

audit dahulu apakah Laravel password reset sudah tersedia.

Jika password reset standar Laravel dapat memenuhi kebutuhan keamanan, jelaskan.

Jangan membuat sistem password reset custom yang tidak aman tanpa alasan.

---

# 27. EMAIL VERIFICATION

Register harus memiliki:

**Email Verification**

Audit:

* User model;
* `MustVerifyEmail`;
* middleware `verified`;
* notification;
* mail configuration;
* routes.

Jangan menganggap email benar-benar terkirim hanya karena notification dibuat.

---

# 28. SETTINGS

Audit fitur:

* account settings;
* profile;
* password;
* notification;
* privacy jika tersedia.

Jangan membuat halaman setting kosong hanya agar requirement terlihat selesai.

---

# 29. HELP CENTER

Pusat bantuan harus dapat diakses publik jika requirement mengharuskannya.

Jangan memberikan middleware auth pada halaman publik.

---

# 30. TERMS & PRIVACY

Sebelum:

**Daftar Sekarang**

harus tersedia akses ke:

* Kebijakan Privasi
* Ketentuan Layanan

Audit apakah halaman tersebut sudah tersedia.

---

# 31. ADMIN DASHBOARD FILTER

Admin membutuhkan filter:

```text
Bulan
Tahun
Total
```

Untuk data yang relevan.

Audit query existing terlebih dahulu.

Jangan memuat seluruh database ke memory PHP hanya untuk melakukan filter jika query database dapat digunakan.

---

# 32. FREELANCER JOB RECOMMENDATION

Requirement:

Bagian rekomendasi pekerjaan yang sudah selesai dihilangkan.

Diganti dengan:

**Pekerjaan terbaru dengan status Open**

Pastikan query hanya mengambil project yang memang dapat ditawarkan/diikuti freelancer.

---

# 33. FOOTER

Footer berada di landing page.

Semua link harus berfungsi.

Audit satu per satu:

* About;
* Help;
* Privacy;
* Terms;
* Contact;
* lainnya.

Jangan membuat link palsu.

---

# 34. COMPANY CONTACT ADMIN

Company harus dapat menghubungi admin.

Audit apakah sistem message/report/help sudah dapat digunakan.

Gunakan sistem yang sudah ada jika memungkinkan.

---

# 35. SIDEBAR

Requirement:

Ketika sidebar ditutup:

**Logo tetap terlihat.**

Audit CSS/JS/layout terlebih dahulu.

Jangan mengubah struktur layout besar-besaran untuk masalah visual sederhana.

---

# 36. MOBILE UI

Project harus dioptimalkan untuk mobile.

Audit:

* overflow;
* navbar;
* sidebar;
* table;
* modal;
* form;
* button;
* payment UI;
* workspace;
* dashboard.

Jangan merusak desktop UI ketika memperbaiki mobile UI.

---

# 37. POPUP / PREMIUM PRO

Requirement:

Popup harus diperbarui.

Premium Pro juga memiliki popup upgrade.

Audit:

* modal;
* component;
* JS;
* trigger;
* responsive layout.

Jangan membuat popup baru jika component existing dapat digunakan.

---

# 38. WORKSPACE INITIAL PROGRESS

Requirement:

Workspace awal jangan dianggap:

**100%**

Audit bagaimana progress dihitung.

Workspace baru harus memiliki progress yang masuk akal berdasarkan tahap awal.

---

# 39. COMPANY WORKSPACE STAGES

Company juga dapat menambahkan tahap pengerjaan.

Audit struktur:

* progress histories;
* stages;
* workspace;
* ordering;
* permissions.

Jika struktur stage sudah tersedia, gunakan struktur tersebut.

---

# 40. NEGOTIATION

Penawaran proyek harus dapat dinegosiasikan.

Audit:

* Penawaran;
* harga;
* pesan;
* status;
* revision;
* acceptance.

Jangan membuat sistem chat baru jika messages system sudah dapat digunakan.

---

# 41. ADMIN REVENUE

Admin harus dapat melihat:

* platform fee;
* total transaksi;
* pendapatan platform;
* payment status;
* periode;
* proyek terkait.

Jangan mencampurkan:

```text
total payment
```

dengan:

```text
platform revenue
```

Keduanya berbeda.

---

# 42. DATABASE SAFETY

Jika migration sudah pernah dijalankan:

**JANGAN mengedit migration lama sembarangan.**

Periksa:

```bash
php artisan migrate:status
```

Jika:

```text
Ran
```

maka perubahan schema baru harus menggunakan migration baru kecuali project memang sengaja di-reset.

Jangan menyarankan:

```bash
php artisan migrate:fresh
```

tanpa peringatan karena command tersebut menghapus database.

---

# 43. PAYMENT MODEL SAFETY

Audit `Payment` secara khusus.

Periksa:

```text
fillable
casts
relations
company_id
workspace_id
amount
platform_fee
freelancer_receive
status
invoice_number
```

Pastikan pembuatan Payment tidak menghasilkan error:

```text
Field 'company_id' doesn't have a default value
```

Jangan hanya menambahkan default value ke database untuk menutupi bug jika sebenarnya controller/model tidak mengirim `company_id`.

Cari root cause terlebih dahulu.

---

# 44. PSR-4

Jika ditemukan:

```text
does not comply with PSR-4 autoloading standard
```

audit:

* namespace;
* nama class;
* nama folder;
* casing folder;
* casing filename.

Contoh:

```text
App\Http\Controllers\Review
```

harus konsisten dengan:

```text
app/Http/Controllers/Review
```

Windows bisa terlihat tidak bermasalah karena filesystem case-insensitive, tetapi deployment Linux dapat gagal.

---

# 45. GIT SAFETY

Sebelum perubahan besar:

```bash
git status
git log --oneline -10
```

Jika working tree memiliki perubahan user:

**JANGAN menghapusnya.**

Jika ada untracked file:

**JANGAN langsung menghapusnya.**

Tanyakan terlebih dahulu.

Sebelum migration/payment/auth perubahan besar, sarankan commit atau backup.

---

# 46. AUDIT COMMAND

Gunakan command yang relevan seperti:

```bash
git status
git log --oneline --decorate -10
git grep -in "paypal"
git grep -in "payment"
git grep -in "workspace"
git grep -in "balance"
git grep -in "withdraw"
git grep -in "report"
git grep -in "admin"
php artisan route:list
php artisan migrate:status
composer show laravel/framework
php artisan about
```

Namun command hanya digunakan jika environment mengizinkannya.

Jangan mengklaim command telah dijalankan jika belum dijalankan.

---

# 47. AUDIT REPORT

Setiap audit harus menghasilkan:

## SAFE

Hal yang sudah benar.

## WARNING

Hal yang berpotensi bermasalah.

## ERROR

Hal yang benar-benar salah.

## MISSING

Requirement yang belum ditemukan.

## PAYPAL REMNANTS

Kode PayPal yang masih aktif.

## MIDTRANS READINESS

Apakah project siap implementasi Midtrans.

## DATABASE IMPACT

Perubahan database yang diperlukan.

## SECURITY IMPACT

Risiko keamanan.

## FILES AFFECTED

File yang kemungkinan perlu diubah.

---

# 48. IMPLEMENTATION RULE

Setelah audit selesai:

**JANGAN langsung implementasi.**

Tunggu persetujuan user.

Setelah user mengizinkan:

```text
Implement
 ↓
Syntax check
 ↓
Autoload check
 ↓
Route check
 ↓
Migration status
 ↓
Feature test
 ↓
Error log check
```

---

# 49. TESTING RULE

Setelah perubahan payment:

Minimal test:

```text
Company authorization
Payment creation
Midtrans transaction
Notification
Payment verification
Duplicate notification
Invalid transaction
Wrong amount
Wrong workspace
Wrong company
Payment status transition
Workspace transition
```

Jangan menyatakan:

**"100% berhasil"**

hanya karena satu tombol berhasil diklik.

---

# 50. ERROR HANDLING

Jika terjadi error:

Jangan langsung menambal error.

Cari:

```text
Error
 ↓
Stack trace
 ↓
Controller
 ↓
Service
 ↓
Model
 ↓
Database
```

Kemudian tentukan root cause.

Contoh:

```text
SQLSTATE 1364
```

Jangan langsung menambahkan:

```php
'default' => ...
```

tanpa memahami mengapa field tersebut kosong.

---

# 51. RESPONSE STYLE

Saat membantu project ini:

* Jangan memberikan jawaban berdasarkan asumsi.
* Jangan mengarang file.
* Jangan mengarang route.
* Jangan mengarang migration.
* Jangan mengarang field.
* Jangan menghapus kode tanpa alasan.
* Jangan melakukan refactor besar tanpa izin.
* Jangan mengubah database secara diam-diam.
* Jangan mengatakan "sudah diperbaiki" jika belum diuji.
* Jangan mengatakan "pasti berhasil" jika belum diuji.

Jika tidak yakin:

**Katakan tidak yakin dan minta source code yang diperlukan.**

---

# 52. PRIORITAS

Urutan prioritas:

1. Data integrity
2. Payment security
3. Authorization
4. Authentication
5. Database consistency
6. Business logic
7. Error handling
8. Testing
9. UI/UX
10. Cosmetic changes

Jangan mengorbankan keamanan/database hanya demi UI.

---

# 53. TARGET ARCHITECTURE

Target besar sistem:

```text
USER
│
├── Freelancer
│   ├── Profile
│   ├── Browse Projects
│   ├── Submit Offer
│   ├── Negotiate
│   ├── Workspace
│   ├── Submission
│   ├── Earnings
│   └── Withdrawal
│
├── Company
│   ├── Profile
│   ├── Create Project
│   ├── Manage Project
│   ├── Receive Offer
│   ├── Negotiate
│   ├── Workspace
│   ├── Payment
│   ├── Expense
│   └── Dispute
│
└── Admin
    ├── Users
    ├── Projects
    ├── Categories
    ├── Offers
    ├── Workspaces
    ├── Payments
    ├── Reports
    ├── Disputes
    ├── Revenue
    └── Withdrawals
```

---

# 54. PAYMENT TARGET ARCHITECTURE

```text
Company
   ↓
Accept Freelancer
   ↓
Workspace
   ↓
Payment Invoice
   ↓
Midtrans
   ↓
Midtrans Notification
   ↓
Backend Verification
   ↓
Payment = PAID
   ↓
Workspace = IN PROGRESS
   ↓
Freelancer Works
   ↓
Submission
   ↓
Company Review
   ↓
┌──────────────────────┐
│                      │
Accept               Revision
│                      │
↓                      ↓
Completed            Work Again
│
↓
Settlement
│
↓
Freelancer Earnings
```

---

# 55. DISPUTE ARCHITECTURE

```text
Company / Freelancer
        ↓
      Report
        ↓
   Admin Review
        ↓
     Evidence
        ↓
Admin Decision
   │      │      │
   ↓      ↓      ↓
Release Refund Other
```

Keputusan admin harus memiliki audit trail.

---

# 56. FINAL RULE

**READ THE PROJECT FIRST.**

**DO NOT GUESS.**

**DO NOT BREAK EXISTING FEATURES.**

**DO NOT MODIFY DATABASE WITHOUT A PLAN.**

**DO NOT TRUST FRONTEND PAYMENT DATA.**

**DO NOT REINTRODUCE PAYPAL.**

**USE MIDTRANS FOR INDONESIAN PAYMENT.**

**KEEP PAYMENT, WORKSPACE, EARNINGS, AND DISPUTE STATES SEPARATE.**

**ALWAYS AUDIT BEFORE IMPLEMENTATION.**

---

# 57. FIRST TASK WHEN PROMPT.MD IS PROVIDED

Ketika user meminta AI mulai bekerja menggunakan file ini, langkah pertama WAJIB:

1. Baca `PROMPT.md`.
2. Baca struktur project.
3. Jalankan/analisis `git status`.
4. Analisis `git log`.
5. Analisis migration status.
6. Analisis route.
7. Cari PayPal.
8. Audit Payment.
9. Audit Workspace.
10. Audit Submission.
11. Audit Report/Dispute.
12. Audit Earnings/Withdrawal.
13. Buat laporan audit.

**Jangan mengubah kode pada tahap ini.**

Tunggu instruksi berikutnya dari user.
