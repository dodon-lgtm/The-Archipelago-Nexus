# TODO - Fitur Permintaan Akun Perusahaan (Tugas 3.5)

- [x] Tambahkan migration tabel `company_account_requests` sesuai spesifikasi.
- [x] Buat model `CompanyAccountRequest`.
- [x] Buat FormRequest validasi `CompanyAccountRequestStoreRequest` (wajib isi, format email, email unik terhadap `users`, cek request aktif status `menunggu`).
- [x] Buat controller `CompanyAccountRequestController` (tampilkan form + simpan request dengan status awal `menunggu`).
- [x] Tambahkan route publik untuk halaman form dan proses submit.
- [x] Buat Blade view form menggunakan Bootstrap 5 + tampilkan error di bawah input.
- [x] Tambahkan link/menu "Ajukan Akun Perusahaan" di halaman beranda (`welcome.blade.php`) untuk user yang belum login.
- [x] Jalankan `php artisan migrate` dan validasi alur manual (email sudah ada / ada request menunggu / sukses submit).



