<?php

namespace Database\Seeders;

use App\Models\Policy;
use Illuminate\Database\Seeder;

class PolicySeeder extends Seeder
{
    /**
     * Seeder bawaan untuk dokumen kebijakan.
     * Menggunakan firstOrCreate / updateOrCreate agar idempotent di semua environment.
     */
    public function run(): void
    {
        $policies = [
            [
                'key'       => Policy::KEY_PRIVACY,
                'title'     => 'Kebijakan Privasi',
                'is_active' => true,
                'content'   => implode("\n\n", [
                    'ApexForge Labs menghargai dan melindungi privasi Anda. Data pribadi seperti nama, email, nomor telepon, dan informasi profil hanya digunakan untuk keperluan identifikasi, komunikasi terkait proyek, verifikasi akun, serta peningkatan kualitas layanan.',
                    'Kami tidak akan membagikan, menjual, atau menyewakan data pribadi Anda kepada pihak ketiga tanpa persetujuan, kecuali diwajibkan oleh hukum yang berlaku. Seluruh data disimpan secara aman dan hanya dapat diakses oleh pihak yang berwenang.',
                    'Anda berhak untuk memperbarui, memperbaiki, atau menghapus data pribadi Anda melalui fitur pengaturan profil. Dengan menggunakan platform ini, Anda menyetujui pemrosesan data sebagaimana dijelaskan dalam kebijakan ini.',
                ]),
            ],
            [
                'key'       => Policy::KEY_USAGE,
                'title'     => 'Kebijakan Penggunaan',
                'is_active' => true,
                'content'   => implode("\n\n", [
                    'Pengguna wajib memberikan informasi yang benar, akurat, dan tidak menyesatkan saat mendaftar atau menggunakan layanan. Setiap akun bersifat pribadi dan tidak boleh digunakan oleh pihak lain tanpa izin.',
                    'Dilarang menggunakan platform untuk aktivitas ilegal, penipuan, spam, penyebaran konten melanggar hukum, serta tindakan yang merugikan pengguna lain atau platform. Pelanggaran dapat berakibat pada pemblokiran atau penghapusan akun.',
                    'Seluruh transaksi, negosiasi, dan interaksi antar pengguna dilakukan secara mandiri di dalam platform yang aman. ApexForge Labs berhak meninjau, menunda, atau menolak layanan apabila ditemukan indikasi pelanggaran atas kebijakan ini.',
                ]),
            ],
        ];

        foreach ($policies as $payload) {
            Policy::query()->updateOrCreate(
                ['key' => $payload['key']],
                $payload
            );
        }
    }
}
