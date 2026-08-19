<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class HelpCenterController extends Controller
{
    /**
     * Display the Help Center page (public).
     */
    public function index()
    {
        $faqs = $this->getFaqs();
        $categories = $this->getCategories();

        return view('help.index', compact('faqs', 'categories'));
    }

    /**
     * Store contact form submission (public).
     * Stores as a website report in the reports table.
     */
    public function storeContact(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ], [
            'name.required'    => 'Nama wajib diisi.',
            'email.required'   => 'Email wajib diisi.',
            'email.email'      => 'Format email tidak valid.',
            'subject.required' => 'Subjek wajib diisi.',
            'message.required' => 'Pesan wajib diisi.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            DB::transaction(function () use ($request) {
                Report::create([
                    'reporter_id'      => null,
                    'reported_user_id' => null,
                    'project_id'       => null,
                    'penawaran_id'     => null,
                    'workspace_id'     => null,
                    'target'           => Report::TARGET_WEBSITE,
                    'subject'          => $request->subject,
                    'description'      => "Nama: {$request->name}\nEmail: {$request->email}\n\n{$request->message}",
                    'category'         => Report::CATEGORY_UMUM,
                    'status'           => Report::STATUS_MENUNGGU,
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Pesan Anda telah terkirim. Tim kami akan menghubungi Anda secepat mungkin.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. Silakan coba lagi nanti.',
            ], 500);
        }
    }

    /**
     * Get FAQ data organized by category.
     */
    private function getFaqs(): array
    {
        return [
            'akun-profil' => [
                'title' => 'Akun & Profil',
                'items' => [
                    [
                        'question' => 'Bagaimana cara membuat akun?',
                        'answer' => 'Klik tombol "Daftar" di pojok kanan atas, pilih peran Anda (Freelancer atau Perusahaan), isi formulir pendaftaran, dan verifikasi email Anda. Akun akan siap digunakan setelah verifikasi.',
                    ],
                    [
                        'question' => 'Bagaimana cara mengajukan akun Company?',
                        'answer' => 'Setelah mendaftar sebagai Freelancer, Anda dapat mengajukan upgrade ke akun Company melalui menu "Pengajuan Akun Company" di dashboard. Isi formulir dengan data perusahaan yang valid dan tunggu verifikasi dari tim Admin.',
                    ],
                    [
                        'question' => 'Bagaimana cara mengubah password?',
                        'answer' => 'Masuk ke Pengaturan > Security > Ubah Password. Masukkan password saat ini, lalu password baru minimal 8 karakter, dan konfirmasi password baru.',
                    ],
                    [
                        'question' => 'Apakah saya bisa memiliki akun Freelancer dan Company sekaligus?',
                        'answer' => 'Satu email hanya dapat terdaftar sebagai satu peran. Jika Anda ingin berperan sebagai Company, ajukan pengajuan upgrade akun melalui dashboard Freelancer.',
                    ],
                ],
            ],
            'proyek' => [
                'title' => 'Proyek',
                'items' => [
                    [
                        'question' => 'Bagaimana cara mencari proyek?',
                        'answer' => 'Gunakan halaman "Cari Proyek" setelah login sebagai Freelancer. Anda dapat memfilter berdasarkan kategori, budget, deadline, dan kata kunci pencarian.',
                    ],
                    [
                        'question' => 'Bagaimana cara mempublikasikan proyek?',
                        'answer' => 'Login sebagai Company, masuk ke dashboard, klik "Tambah Proyek", isi detail proyek (nama, deskripsi, kategori, budget, deadline), dan publish. Proyek akan muncul di marketplace setelah disetujui.',
                    ],
                    [
                        'question' => 'Apa yang terjadi jika proyek tidak mendapat penawaran?',
                        'answer' => 'Proyek akan tetap tampil hingga deadline. Anda dapat memperpanjang deadline atau mengedit detail proyek untuk menarik lebih banyak freelancer.',
                    ],
                ],
            ],
            'penawaran' => [
                'title' => 'Penawaran',
                'items' => [
                    [
                        'question' => 'Bagaimana cara mengirim penawaran?',
                        'answer' => 'Buka detail proyek yang diminati, klik "Kirim Penawaran", isi harga penawaran, estimasi waktu, dan deskripsi pendekatan Anda. Penawaran akan dikirim ke Company pemilik proyek.',
                    ],
                    [
                        'question' => 'Bisakah saya menarik penawaran yang sudah dikirim?',
                        'answer' => 'Ya, selama status penawaran masih "Menunggu", Anda dapat membatalkannya dari halaman "Lamaran Saya". Setelah status berubah menjadi "Diterima" atau "Ditolak", penawaran tidak dapat ditarik kembali.',
                    ],
                    [
                        'question' => 'Berapa banyak penawaran yang bisa saya kirim?',
                        'answer' => 'Tidak ada batas jumlah penawaran, namun disarankan fokus pada proyek yang sesuai keahlian Anda untuk peluang diterima yang lebih besar.',
                    ],
                ],
            ],
            'workspace' => [
                'title' => 'Workspace',
                'items' => [
                    [
                        'question' => 'Bagaimana cara menggunakan Workspace?',
                        'answer' => 'Setelah penawaran diterima, Workspace otomatis dibuat. Di sana Anda bisa: berkomunikasi via chat, mengirim update progress, mengunggah hasil kerja (submission), dan melihat timeline proyek.',
                    ],
                    [
                        'question' => 'Apa itu Submission di Workspace?',
                        'answer' => 'Submission adalah pengiriman hasil kerja oleh Freelancer ke Company untuk direview. Company bisa menerima (Accept) atau meminta revisi (Request Revision).',
                    ],
                    [
                        'question' => 'Bagaimana alur pembayaran di Workspace?',
                        'answer' => 'Setelah Company menerima submission, Company upload bukti pembayaran. Admin verifikasi, lalu dana cair ke Freelancer. Sistem escrow memastikan keamanan kedua belah pihak.',
                    ],
                ],
            ],
            'pembayaran' => [
                'title' => 'Pembayaran',
                'items' => [
                    [
                        'question' => 'Bagaimana proses pembayaran?',
                        'answer' => 'Pembayaran menggunakan sistem escrow: Company membayar ke platform saat proyek dimulai -> Dana diamankan -> Setelah submission diterima, dana dicairkan ke Freelancer.',
                    ],
                    [
                        'question' => 'Metode pembayaran apa yang didukung?',
                        'answer' => 'Saat ini mendukung transfer bank dan PayPal. Detail rekening tersedia di halaman pembayaran Workspace.',
                    ],
                    [
                        'question' => 'Berapa lama proses pencairan dana?',
                        'answer' => 'Setelah Admin memverifikasi bukti pembayaran Company, pencairan ke rekening Freelancer diproses dalam 1-2 hari kerja.',
                    ],
                ],
            ],
            'pendapatan' => [
                'title' => 'Pendapatan',
                'items' => [
                    [
                        'question' => 'Di mana saya bisa melihat pendapatan?',
                        'answer' => 'Freelancer bisa melihat riwayat pendapatan di menu "Pendapatan" di dashboard. Termasuk status: Menunggu, Dicairkan, dan Total.',
                    ],
                    [
                        'question' => 'Kapan pendapatan bisa dicairkan?',
                        'answer' => 'Pendapatan bisa dicairkan setelah status berubah menjadi "Dicairkan" (Company telah membayar dan Admin telah memverifikasi).',
                    ],
                ],
            ],
            'keamanan' => [
                'title' => 'Keamanan',
                'items' => [
                    [
                        'question' => 'Bagaimana ApexForge Labs menjaga keamanan data?',
                        'answer' => 'Kami menggunakan enkripsi SSL, penyimpanan password ter-hash (bcrypt), verifikasi email wajib, dan opsi 2FA. Data pribadi tidak dibagikan ke pihak ketiga tanpa izin.',
                    ],
                    [
                        'question' => 'Apa yang harus saya lakukan jika akun dicuri?',
                        'answer' => 'Segera hubungi support@apexforgelabs.id dengan subjek "AKUN DICURI". Sertakan bukti kepemilikan akun. Tim kami akan mengunci akun dan membantu recovery.',
                    ],
                    [
                        'question' => 'Apakah transaksi di luar platform diizinkan?',
                        'answer' => 'TIDAK. Transaksi di luar platform melanggar Ketentuan Layanan dan berisiko penipuan tanpa perlindungan escrow. Kami tidak bertanggung jawab atas kerugian transaksi luar platform.',
                    ],
                ],
            ],
            'lainnya' => [
                'title' => 'Lainnya',
                'items' => [
                    [
                        'question' => 'Bagaimana cara menghubungi admin?',
                        'answer' => 'Gunakan formulir "Hubungi Admin" di halaman ini, atau email ke support@apexforgelabs.id. Kami merespons dalam 1x24 jam pada hari kerja.',
                    ],
                    [
                        'question' => 'Di mana saya bisa melihat Ketentuan Layanan dan Kebijakan Privasi?',
                        'answer' => 'Tersedia di footer halaman ini: "Ketentuan Layanan" dan "Kebijakan Privasi".',
                    ],
                    [
                        'question' => 'Apakah ada aplikasi mobile?',
                        'answer' => 'Saat ini ApexForge Labs hanya tersedia sebagai web app responsif yang bisa diakses dari browser mobile. Aplikasi native sedang dalam pengembangan.',
                    ],
                ],
            ],
        ];
    }

    /**
     * Get help categories for sidebar.
     */
    private function getCategories(): array
    {
        return [
            ['key' => 'akun-profil', 'label' => 'Akun & Profil', 'icon' => 'fa-user-circle'],
            ['key' => 'proyek', 'label' => 'Proyek', 'icon' => 'fa-briefcase'],
            ['key' => 'penawaran', 'label' => 'Penawaran', 'icon' => 'fa-paper-plane'],
            ['key' => 'workspace', 'label' => 'Workspace', 'icon' => 'fa-layer-group'],
            ['key' => 'pembayaran', 'label' => 'Pembayaran', 'icon' => 'fa-wallet'],
            ['key' => 'pendapatan', 'label' => 'Pendapatan', 'icon' => 'fa-coins'],
            ['key' => 'keamanan', 'label' => 'Keamanan', 'icon' => 'fa-shield-halved'],
            ['key' => 'lainnya', 'label' => 'Lainnya', 'icon' => 'fa-ellipsis-h'],
        ];
    }
}