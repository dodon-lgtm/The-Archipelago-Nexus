<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Regression test halaman /freelancer/pendapatan.
 *
 * Dua hal yang dikunci di sini:
 *  1. Tidak ada Git conflict marker yang bocor sebagai teks di halaman.
 *  2. Empat kartu ringkasan (Saldo Tersedia, Saldo Tertahan/Escrow,
 *     Total Pendapatan, Direfund ke Company) tetap lengkap & konsisten:
 *     ada nilai Rupiah (formatRupiahShort) DAN title nominal penuh.
 *
 * Perbaikan konflik Git pada kartu-kartu ini hanya menyentuh class CSS,
 * sehingga nilai & title harus tetap utuh setelah merge.
 */
class FreelancerPendapatanPageTest extends TestCase
{
    use RefreshDatabase;

    private User $freelancer;

    protected function setUp(): void
    {
        parent::setUp();

        // Halaman pendapatan tidak butuh workspace/proyek: keempat kartu
        // ringkasan dihitung dari saldo & payment milik freelancer itu
        // sendiri, dan tetap dirender (dengan nilai 0) saat belum ada data.
        $this->freelancer = User::factory()->create(['role' => 'freelancer']);
    }

    public function test_halaman_pendapatan_tidak_bocorkan_conflict_marker(): void
    {
        $html = $this->render();

        // Conflict marker Git SELALU berada di awal baris (tanpa indentasi).
        // '=====' di tengah CSS/JS (mis. /* ===== banner ===== */) BUKAN
        // konflik, jadi tidak boleh ikut ter-flag.
        foreach (['<<<<<<<', '>>>>>>>', 'Updated upstream', 'Stashed changes'] as $marker) {
            $this->assertStringNotContainsString(
                $marker,
                $html,
                "Conflict marker \"{$marker}\" masih tampil di halaman pendapatan."
            );
        }

        $this->assertSame(
            0,
            // Git menulis 7 tanda '<' / '>' / '=' persis, diikuti spasi atau
            // akhir baris. Banner CSS seperti "/* ====== */" bukan konflik.
            preg_match('/^[ \t]*(<<<<<<<|=======|>>>>>>>)([ \t].*)?$/m', $html),
            'Baris conflict marker masih tampil di halaman pendapatan.'
        );
    }

    public function test_keempat_kartu_ringkasan_lengkap_dengan_nominal_rupiah(): void
    {
        $html = $this->render();

        // Label kartu harus tampil.
        $this->assertStringContainsString('Saldo Tersedia', $html);
        $this->assertStringContainsString('Saldo Tertahan (Escrow)', $html);
        $this->assertStringContainsString('Total Pendapatan', $html);
        $this->assertStringContainsString('Direfund ke Company', $html);

        // Setiap kartu menyisakan title nominal LENGAP (format Rp penuh dari
        // number_format). Merge tidak boleh membuang salah satunya.
        $this->assertSame(
            4,
            preg_match_all('/title="Rp [\d.,]+"/', $html),
            'Nominal lengkap (title) pada keempat kartu hilang.'
        );

        // Nilai short-form hasil formatRupiahShort juga tetap dirender
        // (untuk saldo 0 hasilnya "Rp 0").
        $this->assertSame(
            4,
            preg_match_all('/Rp 0\s*<\/h3>/', $html),
            'Nilai Rupiah pada keempat kartu hilang.'
        );

        // Class responsif hasil merge harus ada (tidak kembali ke text-2xl polos).
        $this->assertStringContainsString('text-lg sm:text-xl font-black', $html);
    }

    private function render(): string
    {
        $html = $this->actingAs($this->freelancer)
            ->get(route('freelancer.pendapatan.index'))
            ->assertOk()
            ->getContent();

        return $html;
    }
}
