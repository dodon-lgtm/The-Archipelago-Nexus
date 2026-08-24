<?php

namespace Database\Seeders;

use App\Models\FooterSetting;
use Illuminate\Database\Seeder;

class FooterSettingSeeder extends Seeder
{
    /**
     * Seeder bawaan pengaturan footer.
     * Menggunakan firstOrCreate agar idempotent di semua environment.
     */
    public function run(): void
    {
        FooterSetting::query()->firstOrCreate([], [
            'privacy_policy_content'   => implode("\n\n", [
                'ApexForge Labs menghargai dan melindungi privasi Anda. Data pribadi seperti nama, email, nomor telepon, dan informasi profil hanya digunakan untuk keperluan identifikasi, komunikasi terkait proyek, verifikasi akun, serta peningkatan kualitas layanan.',
                'Kami tidak akan membagikan, menjual, atau menyewakan data pribadi Anda kepada pihak ketiga tanpa persetujuan, kecuali diwajibkan oleh hukum yang berlaku. Seluruh data disimpan secara aman dan hanya dapat diakses oleh pihak yang berwenang.',
                'Anda berhak untuk memperbarui, memperbaiki, atau menghapus data pribadi Anda melalui fitur pengaturan profil. Dengan menggunakan platform ini, Anda menyetujui pemrosesan data sebagaimana dijelaskan dalam kebijakan ini.',
            ]),
            'terms_conditions_content'  => implode("\n\n", [
                'Dengan menggunakan platform ApexForge Labs, Anda menyetujui untuk memberikan informasi yang benar, akurat, dan tidak menyesatkan saat mendaftar atau menggunakan layanan. Setiap akun bersifat pribadi dan tidak boleh digunakan oleh pihak lain tanpa izin.',
                'Dilarang menggunakan platform untuk aktivitas ilegal, penipuan, spam, penyebaran konten melanggar hukum, serta tindakan yang merugikan pengguna lain atau platform. Pelanggaran dapat berakibat pada pemblokiran atau penghapusan akun.',
                'Seluruh transaksi, negosiasi, dan interaksi antar pengguna dilakukan secara mandiri di dalam platform yang aman. ApexForge Labs berhak meninjau, menunda, atau menolak layanan apabila ditemukan indikasi pelanggaran atas ketentuan ini.',
            ]),
            'support_email'            => 'kitaayo94@gmail.com',
            'about_text'               => 'Infrastruktur marketplace freelance masa depan. Menghubungkan talenta berbakat Nusantara dengan ekosistem proyek industri kreatif dan teknologi global secara seamless.',
            'copyright_text'           => '© 2026 ApexForge Labs. Hak Cipta Dilindungi.',
        ]);
    }
}