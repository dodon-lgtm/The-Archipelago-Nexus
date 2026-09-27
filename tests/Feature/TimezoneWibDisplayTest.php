<?php

namespace Tests\Feature;

use App\Support\Cast;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/**
 * Regression test timezone / timestamp.
 *
 * KONVENSI PROJECT:
 *  - Penyimpanan timestamp  : UTC (config('app.timezone'))
 *  - Tampilan ke user      : Asia/Jakarta / WIB (config('app.display_timezone'))
 *
 * Bug yang dikunci: halaman /freelancer/pendapatan menampilkan
 * `27 Sep 2026 15:23` untuk kejadian yang sebenarnya terjadi pukul 22:23 WIB
 * (selisih 7 jam) karena nilai UTC di database langsung di-format tanpa
 * konversi timezone.
 *
 * Cast::wib() HARUS memakai setTimezone() (perpindahan zona waktu sungguhan),
 * BUKAN adds(7, 'hours') manual — supaya tidak meleset & tidak bisa ter-shift
 * dua kali.
 */
class TimezoneWibDisplayTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'app.timezone'         => 'UTC',
            'app.display_timezone' => 'Asia/Jakarta',
        ]);
    }

    public function test_konfigurasi_timezone_penyimpanan_dan_tampilan(): void
    {
        // Penyimpanan tetap UTC supaya data lama tidak meleset.
        $this->assertSame('UTC', config('app.timezone'));
        $this->assertSame('Asia/Jakarta', config('app.display_timezone'));
        $this->assertSame('Asia/Jakarta', Cast::displayTimezone());
    }

    public function test_timestamp_utc_ditampilkan_menjadi_wib(): void
    {
        // 15:23 UTC = 22:23 WIB
        $this->assertSame('27 Sep 2026 22:23', Cast::wib('2026-09-27 15:23:00'));
    }

    public function test_waktu_baru_yang_dibuat_ditampilkan_sesuai_waktu_aktual(): void
    {
        $now = now(); // Carbon dalam timezone penyimpanan (UTC)
        $expected = (new \DateTime('now', new \DateTimeZone('Asia/Jakarta')))->format('d M Y H:i');

        $this->assertSame($expected, Cast::wib($now));
    }

    public function test_konversi_tidak_bergeser_ganda(): void
    {
        $utc = Carbon::parse('2026-09-27 15:23:00', 'UTC');

        $sekali = Cast::wib($utc);
        $dua   = Cast::wib(Cast::toWib($utc));

        // Terapkan dua kali harus tetap sama — tidak boleh +14 jam.
        $this->assertSame($sekali, $dua);
        $this->assertSame('27 Sep 2026 22:23', $sekali);
    }

    public function test_tidak_ada_adds_manual_yang_melebihi_konversi(): void
    {
        // Nilai UTC murni harus hanya bergeser pada jam DITAMPILKAN (wall clock),
        // sementara instan absolutnya tidak boleh ikut bergeser.
        $utc = Carbon::parse('2026-09-27 15:23:00', 'UTC');
        $wib = Cast::toWib($utc->copy());

        $selisihJamTampil = (
            strtotime($wib->format('Y-m-d H:i:s')) - strtotime($utc->format('Y-m-d H:i:s'))
        ) / 3600;

        $this->assertSame(7, (int) $selisihJamTampil, 'Jam tampilan harus selisih tepat 7 jam (UTC → WIB).');
        $this->assertSame('27 Sep 2026 22:23', $wib->format('d M Y H:i'));

        // Round-trip: instan aslinya TIDAK berubah (tidak ada timestamp geser).
        $this->assertSame(
            $utc->toDateTimeString(),
            $wib->copy()->setTimezone('UTC')->toDateTimeString(),
            'Nilai yang disimpan tidak boleh ikut bergeser saat konversi.'
        );
    }

    public function test_nilai_kosong_aman(): void
    {
        $this->assertSame('-', Cast::wib(null));
        $this->assertSame('-', Cast::wib(''));
        $this->assertNull(Cast::toWib(null));
    }

    public function test_halaman_pendapatan_merender_timestamp_dalam_wib(): void
    {
        // End-to-end: nilai UTC yang tersimpan di DB HARUS tampil +7 jam (WIB).
        $freelancer = \App\Models\User::factory()->create(['role' => 'freelancer']);
        $company    = \App\Models\User::factory()->create(['role' => 'company']);

        $payment = \App\Models\Payment::create([
            'freelancer_id' => $freelancer->id,
            'company_id' => $company->id,
            'invoice_number' => 'INV-TZ-001',
            'amount' => 1000000,
            'platform_fee' => 50000,
            'platform_fee_rate' => 5,
            'freelancer_receive' => 950000,
            'status' => 'paid',
            'funds_status' => \App\Models\Payment::FUNDS_RELEASED,
            'released_amount' => 950000,
        ]);

        // Stempel waktu penyimpanan: 2026-09-27 15:23 UTC.
        \App\Models\Payment::whereKey($payment->id)
            ->update(['created_at' => '2026-09-27 15:23:00']);

        $html = $this->actingAs($freelancer)
            ->get(route('freelancer.pendapatan.index'))
            ->assertOk()
            ->getContent();

        $this->assertStringContainsString(
            '27 Sep 2026 22:23',
            $html,
            'Halaman pendapatan harus menampilkan 22:23 WIB untuk nilai 15:23 UTC.'
        );

        $this->assertStringNotContainsString(
            '27 Sep 2026 15:23',
            $html,
            'Halaman pendapatan masih menampilkan jam UTC mentah (bug 7 jam).'
        );
    }

    public function test_halaman_pendapatan_merender_waktu_penarikan_dalam_wib(): void
    {
        // Baris kedua yang diperbaiki: waktu penarikan (paid_at ?? created_at).
        $freelancer = \App\Models\User::factory()->create(['role' => 'freelancer']);

        $withdrawal = \App\Models\Withdrawal::create([
            'withdrawal_code' => 'WD-TZ-001',
            'withdrawal_type' => \App\Models\Withdrawal::TYPE_FREELANCER,
            'user_id' => $freelancer->id,
            'amount' => 500000,
            'fee' => 25000,
            'fee_rate' => 5,
            'net_amount' => 475000,
            'method' => \App\Models\Withdrawal::METHOD_BANK,
            'bank_name' => 'BCA',
            'account_name' => 'Test User',
            'account_number' => '1234567890',
            'status' => \App\Models\Withdrawal::STATUS_MENUNGGU,
        ]);

        // Tanpa paid_at → jatuh ke created_at; stempel waktu 15:23 UTC.
        \App\Models\Withdrawal::whereKey($withdrawal->id)
            ->update(['created_at' => '2026-09-27 15:23:00', 'paid_at' => null]);

        $html = $this->actingAs($freelancer)
            ->get(route('freelancer.pendapatan.index'))
            ->assertOk()
            ->getContent();

        $this->assertStringContainsString(
            'WD-TZ-001',
            $html,
            'Kartu riwayat penarikan tidak dirender — fixture test perlu disesuaikan.'
        );

        $this->assertStringContainsString(
            '27 Sep 2026 22:23',
            $html,
            'Waktu penarikan harus tampil 22:23 WIB untuk nilai 15:23 UTC.'
        );

        $this->assertStringNotContainsString(
            '27 Sep 2026 15:23',
            $html,
            'Waktu penarikan masih menampilkan jam UTC mentah (bug 7 jam).'
        );
    }

    public function test_halaman_pendapatan_memakai_konversi_wib(): void
    {
        // Blade mengeksekusi PHP saat render, jadi yang diperiksa adalah
        // SUMBER Blade: tidak boleh ada ->format() mentah atas timestamp.
        $blade = file_get_contents(resource_path('views/freelancer/pendapatan/index.blade.php'));

        $this->assertStringContainsString('Cast::wib($payment->created_at)', $blade);
        $this->assertStringContainsString('Cast::wib($wd->paid_at', $blade);

        // Tidak boleh lagi mem-format timestamp secara langsung.
        $this->assertDoesNotMatchRegularExpression(
            "/(created_at|paid_at|updated_at|verified_at|released_at)->format\(/",
            $blade,
            'Ada timestamp yang masih di-format tanpa konversi timezone di halaman pendapatan.'
        );
    }

    public function test_tidak_ada_adds_7_jam_manual(): void
    {
        // Pendekatan "addHours(7)" rapuh & salah untuk data yang sudah lokal.
        $blade = file_get_contents(resource_path('views/freelancer/pendapatan/index.blade.php'));
        $cast  = file_get_contents(app_path('Support/Cast.php'));

        $this->assertDoesNotMatchRegularExpression(
            '/add(Hours|Minutes|Seconds)\(\s*7/',
            $blade . $cast,
            'Ditemukan penambahan 7 jam manual — harus pakai setTimezone().'
        );
    }
}
