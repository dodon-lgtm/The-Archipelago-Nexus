<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CompanyAccountRequest;
use App\Models\CompanyProfile;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Seeder akun COMPANY + proyek contoh.
 *
 * Yang dibuat oleh seeder ini:
 * 1. 1 akun company (role = company) yang siap login.
 * 2. Pengajuan akun perusahaan (company_account_requests) berstatus
 *    "disetujui" + data profil perusahaan (company_profiles) yang lengkap,
 *    supaya profil dianggap >= 80% oleh ProfileCompletionService dan akun
 *    dapat langsung membuat proyek baru dari UI.
 * 3. Kumpulan proyek milik akun tersebut. Gambar proyek diambil dari
 *    folder `public/images/foto_serius` lalu DISALIN ke
 *    `storage/app/public/projects/images` karena semua view menampilkan
 *    gambar dengan `asset('storage/' . $project->image)`.
 *
 * Seeder ini idempotent (aman dijalankan berulang kali): user dicari
 * berdasarkan email, sedangkan proyek memakai pola updateOrCreate
 * (user_id + project_name).
 */
class CompanyProjectSeeder extends Seeder
{
    // ─── Identitas akun company yang dibuat ───────────────────────────────
    public const COMPANY_NAME     = 'PT Nusantara Karya Digital';
    public const COMPANY_EMAIL    = 'nusantara@archipelagonexus.com';
    public const COMPANY_PASSWORD = 'company123';
    public const COMPANY_PHONE    = '081298765432';
    public const COMPANY_CONTACT  = 'Rangga Prasetyo';

    /** Folder sumber gambar (relatif terhadap public/). */
    private const PHOTO_SOURCE_DIR = 'images/foto_serius';

    /** Folder tujuan gambar di storage publik (relatif storage/app/public). */
    private const PHOTO_TARGET_DIR = 'projects/images';

    public function run(): void
    {
        $email = Str::lower(trim(self::COMPANY_EMAIL));

        // ── 1. Akun company ───────────────────────────────────────────────
        $company = User::query()->where('email', $email)->first();

        if ($company) {
            $company->fill([
                'name'  => self::COMPANY_NAME,
                'phone' => self::COMPANY_PHONE,
                'role'  => 'company',
            ])->save();
        } else {
            $company = User::create([
                'name'     => self::COMPANY_NAME,
                'email'    => $email,
                'phone'    => self::COMPANY_PHONE,
                'password' => Hash::make(self::COMPANY_PASSWORD),
                'role'     => 'company',
            ]);
        }

        // ── 2. Pengajuan akun perusahaan (sudah disetujui admin) ──────────
        CompanyAccountRequest::query()->updateOrCreate(
            ['company_email' => $email],
            [
                'company_name'        => self::COMPANY_NAME,
                'contact_person'      => self::COMPANY_CONTACT,
                'company_phone'       => self::COMPANY_PHONE,
                'company_address'     => 'Jl. Jenderal Sudirman Kav. 52-53, SCBD, Jakarta Selatan 12190',
                'company_description' => 'Digital agency yang bergerak di bidang pengembangan aplikasi web, mobile, dan transformasi digital.',
                'request_status'      => 'disetujui',
                'reviewed_by'         => User::query()->where('role', 'admin')->first()?->id,
                'note'                => 'Seeder: akun perusahaan contoh untuk data demo proyek.',
            ]
        );

        // ── 3. Profil perusahaan (dilengkapi agar >= 80%) ─────────────────
        CompanyProfile::query()->updateOrCreate(
            ['user_id' => $company->id],
            [
                'company_name' => self::COMPANY_NAME,
                'industry'     => 'Teknologi Informasi & Jasa Digital',
                'description'  => 'PT Nusantara Karya Digital adalah perusahaan digital agency yang membantu bisnis melakukan transformasi digital melalui pengembangan aplikasi web, mobile, dan strategi pemasaran digital.',
                'website'      => 'https://nusantarakarya.co.id',
                'location'     => 'Jakarta Selatan, DKI Jakarta',
                'phone'        => self::COMPANY_PHONE,
            ]
        );

        // ── 4. Proyek + gambar dari public/images/foto_serius ─────────────
        if (!File::exists(public_path('storage'))) {
            $this->command?->warn('Symlink storage belum ada. Jalankan dulu: php artisan storage:link');
        }

        $total = 0;

        foreach ($this->projectBlueprints() as $blueprint) {
            $imagePath = $this->publishProjectImage($blueprint['image']);

            Project::query()->updateOrCreate(
                [
                    'user_id'      => $company->id,
                    'project_name' => $blueprint['project_name'],
                ],
                [
                    'category_id'         => $this->categoryId($blueprint['category']),
                    'project_description' => $blueprint['project_description'],
                    'budget'              => $blueprint['budget'],
                    'deadline'            => now()->addDays($blueprint['deadline_in_days'])->toDateString(),
                    'skills'              => $blueprint['skills'],
                    'image'               => $imagePath,
                    'status'              => Project::STATUS_OPEN,
                    'stages'              => $this->stageItems($blueprint['stages'], $company->id),
                ]
            );

            $total++;
        }

        $this->command?->info(sprintf(
            'CompanyProjectSeeder: akun company %s (%s) siap dipakai, %d proyek ditambahkan.',
            $email,
            self::COMPANY_NAME,
            $total
        ));
    }

    /**
     * Ambil (atau buat bila belum ada) id kategori master berdasarkan nama.
     * Kategori normalnya sudah dibuat CategorySeeder; firstOrCreate di sini
     * hanya jaring pengaman bila seeder ini dijalankan sendiri.
     */
    private function categoryId(string $name): ?int
    {
        return Category::query()->firstOrCreate(['name' => $name])->id;
    }

    /**
     * Salin gambar dari `public/images/foto_serius` ke `storage/app/public`.
     *
     * Semua view menampilkan gambar proyek dengan
     * `asset('storage/' . $project->image)`, jadi file harus berada di dalam
     * `storage/app/public` dan symlink `public/storage` harus ada.
     *
     * @return string|null Path relatif yang disimpan di kolom `projects.image`.
     */
    private function publishProjectImage(string $filename): ?string
    {
        $source = public_path(self::PHOTO_SOURCE_DIR . '/' . $filename);

        if (!File::exists($source)) {
            $this->command?->warn('Gambar tidak ditemukan, dilewati: ' . $source);

            return null;
        }

        $relativePath = self::PHOTO_TARGET_DIR . '/' . $filename;
        $destination  = storage_path('app/public/' . $relativePath);

        File::ensureDirectoryExists(dirname($destination));

        if (!File::exists($destination)) {
            File::copy($source, $destination);
        }

        return $relativePath;
    }

    /**
     * Normalisasi daftar tahap menjadi format `projects.stages`:
     * [{name, description, created_by}, ...] (format identik dengan
     * project_workspaces.stages yang di-snapshot saat workspace dibuat).
     *
     * @param  array<int, string>  $names
     * @return array<int, array<string, mixed>>
     */
    private function stageItems(array $names, int $userId): array
    {
        return array_map(static fn (string $name): array => [
            'name'        => $name,
            'description' => null,
            'created_by'  => $userId,
        ], array_values($names));
    }

    /**
     * Daftar proyek yang dibuat untuk akun company di atas.
     *
     * Setiap entri memakai satu gambar dari `public/images/foto_serius`
     * (7 file tersedia → 7 proyek).
     *
     * @return array<int, array<string, mixed>>
     */
    private function projectBlueprints(): array
    {
        return [
            [
                'project_name'        => 'Pengembangan Website Company Profile & Portal Karier',
                'project_description' => 'Membangun website company profile perusahaan beserta portal karier (daftar lowongan, form lamaran, dan dashboard HR sederhana). Desain responsif, SEO friendly, dan terintegrasi dengan sistem rekrutmen internal.',
                'category'            => 'Web Development',
                'budget'              => 25000000,
                'deadline_in_days'    => 30,
                'skills'              => 'Laravel, PHP, Tailwind CSS, MySQL, REST API',
                'image'               => '037d7bc224f6c30f4b7480ef3e9f293a.jpg',
                'stages'              => ['Analisis Kebutuhan', 'Desain UI/UX', 'Implementasi Frontend', 'Implementasi Backend', 'Testing & Deployment'],
            ],
            [
                'project_name'        => 'Aplikasi Mobile Absensi Karyawan (Android & iOS)',
                'project_description' => 'Aplikasi absensi karyawan berbasis mobile dengan fitur presensi GPS, selfie, pengajuan cuti, riwayat kehadiran, serta notifikasi real-time ke HRD.',
                'category'            => 'Mobile Development',
                'budget'              => 45000000,
                'deadline_in_days'    => 45,
                'skills'              => 'Flutter, Dart, REST API, Firebase, Google Maps API',
                'image'               => '20aab07e033deb50a85d9a50d75dbbd7.jpg',
                'stages'              => ['Analisis Kebutuhan', 'Desain Aplikasi', 'Modul Absensi', 'Integrasi API & Firebase', 'Testing & Rilis Store'],
            ],
            [
                'project_name'        => 'Redesign UI/UX Dashboard Internal Perusahaan',
                'project_description' => 'Perancangan ulang antarmuka dashboard internal agar lebih modern dan efisien. Dibutuhkan riset pengguna, wireframe, high fidelity mockup, serta design system yang siap dipakai tim developer.',
                'category'            => 'UI/UX Design',
                'budget'              => 18000000,
                'deadline_in_days'    => 21,
                'skills'              => 'Figma, UI Design, UX Research, Design System, Prototyping',
                'image'               => '311144c97696b2053c3ec4d70087c203.jpg',
                'stages'              => ['Riset Pengguna', 'Wireframe & User Flow', 'High Fidelity Mockup', 'Design System & Handoff'],
            ],
            [
                'project_name'        => 'Desain Identitas Visual & Company Profile Kit',
                'project_description' => 'Pembuatan identitas visual perusahaan: logo, palet warna, tipografi, template company profile, kartu nama, dan template presentasi korporat.',
                'category'            => 'Graphic Design',
                'budget'              => 12000000,
                'deadline_in_days'    => 14,
                'skills'              => 'Adobe Illustrator, Photoshop, Branding, Typography',
                'image'               => '7d56b15f51ce37be2d9d1f4af0dcbf31.jpg',
                'stages'              => ['Eksplorasi Konsep', 'Desain Logo', 'Brand Guideline', 'Aset Final & File Cetak'],
            ],
            [
                'project_name'        => 'Kampanye Digital Marketing Peluncuran Produk Baru',
                'project_description' => 'Perencanaan dan eksekusi kampanye digital untuk peluncuran produk baru: riset audiens, content plan satu bulan, iklan Meta & Google Ads, serta laporan performa mingguan.',
                'category'            => 'Digital Marketing',
                'budget'              => 15000000,
                'deadline_in_days'    => 28,
                'skills'              => 'Meta Ads, Google Ads, Copywriting, Analytics, SEO',
                'image'               => '92531a96956ecb5b8c3b684bd7279236.jpg',
                'stages'              => ['Riset Audiens & Kompetitor', 'Strategi & Content Plan', 'Eksekusi Iklan', 'Optimasi & Laporan'],
            ],
            [
                'project_name'        => 'Migrasi & Input Data Master Pelanggan ke CRM',
                'project_description' => 'Membersihkan, memvalidasi, dan memindahkan data pelanggan dari spreadsheet lama ke sistem CRM baru, termasuk pengecekan duplikasi dan penyusunan laporan rekap.',
                'category'            => 'Data Entry',
                'budget'              => 8000000,
                'deadline_in_days'    => 20,
                'skills'              => 'Microsoft Excel, Google Sheets, Data Cleaning, CRM',
                'image'               => '953a25523512610d0a2965dad6bceb1d.jpg',
                'stages'              => ['Audit Data Lama', 'Pembersihan Data', 'Input ke CRM', 'Validasi & Laporan'],
            ],
            [
                'project_name'        => 'Penulisan Konten Blog & Optimasi SEO 20 Artikel',
                'project_description' => 'Menulis 20 artikel blog bertema transformasi digital dan teknologi bisnis. Setiap artikel dioptimasi SEO (keyword research, meta description, internal linking) sebelum publikasi.',
                'category'            => 'Content Writing',
                'budget'              => 6000000,
                'deadline_in_days'    => 60,
                'skills'              => 'Content Writing, SEO, Keyword Research, WordPress',
                'image'               => 'ade61934ee0aab5b9446a3dd4a81c87b.jpg',
                'stages'              => ['Keyword Research', 'Outline & Struktur', 'Penulisan Artikel', 'Optimasi SEO & Publikasi'],
            ],
        ];
    }
}
