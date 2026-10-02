<?php

namespace Tests\Feature;

use App\Models\CompanyAccountRequest;
use App\Models\NegotiationMessage;
use App\Models\Notification;
use App\Models\Penawaran;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NegotiationBadgeTest extends TestCase
{
    use RefreshDatabase;

    private User $company;
    private User $otherCompany;
    private User $freelancer;
    private Project $projectA;
    private Project $projectB;
    private Penawaran $penawaranA;
    private Penawaran $penawaranB;

    protected function setUp(): void
    {
        parent::setUp();

        $this->company = $this->createApprovedCompany('PT Alpha');
        $this->otherCompany = $this->createApprovedCompany('PT Beta');
        $this->freelancer = User::factory()->create(['role' => 'freelancer']);

        $this->projectA = Project::factory()->create(['user_id' => $this->company->id]);
        $this->projectB = Project::factory()->create(['user_id' => $this->company->id]);

        $this->penawaranA = $this->createPenawaran($this->projectA);
        $this->penawaranB = $this->createPenawaran($this->projectB);
    }

    private function createApprovedCompany(string $name): User
    {
        $user = User::factory()->create(['role' => 'company', 'phone' => '081234567890']);

        CompanyAccountRequest::create([
            'company_name'    => $name,
            'contact_person'  => $user->name,
            'company_email'   => $user->email,
            'company_phone'   => '080000000000',
            'company_address' => 'Jl. Uji No. 1',
            'request_status'  => 'disetujui',
        ]);

        return $user;
    }

    private function createPenawaran(Project $project): Penawaran
    {
        return Penawaran::create([
            'project_id'      => $project->id,
            'freelancer_id'   => $this->freelancer->id,
            'harga_penawaran' => 1000000,
            'estimasi_hari'   => 7,
            'pesan'           => 'Lamaran saya.',
            'status'          => 'Menunggu',
        ]);
    }

    private function sendMessage(User $as, Penawaran $penawaran, string $message = 'Halo'): void
    {
        $this->actingAs($as)
            ->postJson("/negotiations/{$penawaran->id}/send", ['message' => $message])
            ->assertCreated();
    }

    private function badgePattern(Penawaran $penawaran, int $count): string
    {
        return 'data-nego-unread="' . $penawaran->id . '"';
    }

    // ─── FREELANCER: BADGE DI HALAMAN LAMARAN ────────────────

    public function test_company_message_shows_unread_badge_on_lamaran_page(): void
    {
        $this->sendMessage($this->company, $this->penawaranA);

        $page = $this->actingAs($this->freelancer)
            ->get(route('freelancer.lamaran'))
            ->assertOk();

        $content = $page->getContent();
        $this->assertStringContainsString($this->badgePattern($this->penawaranA, 1), $content);

        // Badge menampilkan angka 1 untuk penawaran A.
        $this->assertMatchesRegularExpression(
            '/data-nego-unread="' . $this->penawaranA->id . '"[^>]*>\s*1\s*</',
            $content
        );
    }

    public function test_two_unread_messages_show_badge_two(): void
    {
        $this->sendMessage($this->company, $this->penawaranA, 'pesan 1');
        $this->sendMessage($this->company, $this->penawaranA, 'pesan 2');

        $content = $this->actingAs($this->freelancer)
            ->get(route('freelancer.lamaran'))
            ->assertOk()
            ->getContent();

        $this->assertMatchesRegularExpression(
            '/data-nego-unread="' . $this->penawaranA->id . '"[^>]*>\s*2\s*</',
            $content
        );
    }

    public function test_other_project_messages_do_not_leak_into_other_badge(): void
    {
        // Project B mendapat 2 pesan; project A hanya 1.
        $this->sendMessage($this->company, $this->penawaranA, 'untuk A');
        $this->sendMessage($this->company, $this->penawaranB, 'untuk B 1');
        $this->sendMessage($this->company, $this->penawaranB, 'untuk B 2');

        $content = $this->actingAs($this->freelancer)
            ->get(route('freelancer.lamaran'))
            ->assertOk()
            ->getContent();

        $this->assertMatchesRegularExpression(
            '/data-nego-unread="' . $this->penawaranA->id . '"[^>]*>\s*1\s*</',
            $content
        );
        $this->assertMatchesRegularExpression(
            '/data-nego-unread="' . $this->penawaranB->id . '"[^>]*>\s*2\s*</',
            $content
        );
    }

    public function test_opening_conversation_marks_notifications_read_and_badge_disappears(): void
    {
        $this->sendMessage($this->company, $this->penawaranA, 'pesan 1');
        $this->sendMessage($this->company, $this->penawaranA, 'pesan 2');

        // Buka percakapan → notifikasi negotiation.message penawaran ini dibaca.
        $this->actingAs($this->freelancer)
            ->getJson("/negotiations/{$this->penawaranA->id}")
            ->assertOk()
            ->assertJsonPath('unread_negotiation_count', 0);

        $remaining = Notification::where('type', 'negotiation.message')
            ->where('penawaran_id', $this->penawaranA->id)
            ->where('is_read', false)
            ->count();
        $this->assertSame(0, $remaining);

        // Badge hilang dan tetap hilang setelah refresh (konsisten dengan DB).
        for ($i = 0; $i < 2; $i++) {
            $content = $this->actingAs($this->freelancer)
                ->get(route('freelancer.lamaran'))
                ->assertOk()
                ->getContent();

            $this->assertStringNotContainsString(
                $this->badgePattern($this->penawaranA, 2),
                $content
            );
        }
    }

    public function test_freelancer_cannot_mark_read_other_users_negotiation(): void
    {
        $this->sendMessage($this->company, $this->penawaranA);

        // Freelancer lain tidak berhak membuka percakapan ini.
        $outsider = User::factory()->create(['role' => 'freelancer']);
        $this->actingAs($outsider)
            ->getJson("/negotiations/{$this->penawaranA->id}")
            ->assertForbidden();

        $unread = Notification::where('type', 'negotiation.message')
            ->where('is_read', false)
            ->count();
        $this->assertSame(1, $unread);
    }

    // ─── COMPANY: BADGE DI HALAMAN PROJECTS ──────────────────

    public function test_badge_on_freelancer_row_reappears_after_new_message_post_read(): void
    {
        $this->sendMessage($this->freelancer, $this->penawaranA, 'pesan 1');

        // Company buka chat → semua pesan dibaca → badge hilang.
        $this->actingAs($this->company)
            ->getJson("/negotiations/{$this->penawaranA->id}")
            ->assertOk();

        $content = $this->actingAs($this->company)
            ->get(route('company.projects.show', $this->projectA))
            ->assertOk()
            ->getContent();
        $this->assertStringNotContainsString(
            'data-nego-unread="' . $this->penawaranA->id . '"',
            $content
        );

        // Freelancer kirim pesan baru → badge muncul kembali dengan angka 1.
        $this->sendMessage($this->freelancer, $this->penawaranA, 'pesan baru');

        $content = $this->actingAs($this->company)
            ->get(route('company.projects.show', $this->projectA))
            ->assertOk()
            ->getContent();
        $this->assertMatchesRegularExpression(
            '/data-nego-unread="' . $this->penawaranA->id . '"[^>]*>\s*1\s*</',
            $content
        );
    }

    public function test_badge_caps_at_nine_plus_for_many_unread(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $this->sendMessage($this->freelancer, $this->penawaranA, "pesan {$i}");
        }

        $content = $this->actingAs($this->company)
            ->get(route('company.projects.show', $this->projectA))
            ->assertOk()
            ->getContent();

        $this->assertMatchesRegularExpression(
            '/data-nego-unread="' . $this->penawaranA->id . '"[^>]*>\s*9\+\s*</',
            $content
        );
        $this->assertStringNotContainsString('>10<', $content);
    }

    public function test_row_displays_freelancer_name_not_company_name(): void
    {
        $named = User::factory()->create(['role' => 'freelancer', 'name' => 'Rizky Nugraha']);
        $penawaran = Penawaran::create([
            'project_id'      => $this->projectA->id,
            'freelancer_id'   => $named->id,
            'harga_penawaran' => 900000,
            'estimasi_hari'   => 5,
            'pesan'           => 'Lamaran.',
            'status'          => 'Menunggu',
        ]);

        $content = $this->actingAs($this->company)
            ->get(route('company.projects.show', $this->projectA))
            ->assertOk()
            ->getContent();

        // Nama freelancer tampil pada elemen nama baris penawaran.
        $this->assertMatchesRegularExpression(
            '/data-freelancer-name[^>]*>\s*Rizky\s+Nugraha\s*</',
            $content
        );

        // Nama pemilik proyek (company) TIDAK ditampilkan sebagai nama freelancer.
        $this->assertDoesNotMatchRegularExpression(
            '/data-freelancer-name[^>]*>\s*' . preg_quote($this->company->name, '/') . '\s*</',
            $content
        );
    }

    public function test_project_row_shows_negotiation_badge_on_projects_index(): void
    {
        $this->sendMessage($this->freelancer, $this->penawaranA);

        $content = $this->actingAs($this->company)
            ->get(route('company.projects.index'))
            ->assertOk()
            ->getContent();

        $this->assertStringContainsString('data-nego-unread-project="' . $this->projectA->id . '"', $content);
        // Project B tanpa pesan tidak menampilkan badge.
        $this->assertStringNotContainsString('data-nego-unread-project="' . $this->projectB->id . '"', $content);
    }

    public function test_three_unread_messages_show_badge_three_on_project_detail(): void
    {
        for ($i = 1; $i <= 3; $i++) {
            $this->sendMessage($this->freelancer, $this->penawaranA, "pesan {$i}");
        }

        $content = $this->actingAs($this->company)
            ->get(route('company.projects.show', array_merge([$this->projectA], ['sort' => 'default'])))
            ->assertOk()
            ->getContent();

        $this->assertMatchesRegularExpression(
            '/data-nego-unread="' . $this->penawaranA->id . '"[^>]*>\s*3\s*</',
            $content
        );

        // Membaca percakapan → badge hilang sesuai database.
        $this->actingAs($this->company)
            ->getJson("/negotiations/{$this->penawaranA->id}")
            ->assertOk();

        $content = $this->actingAs($this->company)
            ->get(route('company.projects.show', $this->projectA))
            ->assertOk()
            ->getContent();

        $this->assertStringNotContainsString(
            $this->badgePattern($this->penawaranA, 3),
            $content
        );
    }

    public function test_other_company_cannot_see_or_affect_foreign_project_badge(): void
    {
        $this->sendMessage($this->freelancer, $this->penawaranA);

        // Company lain tidak melihat badge project milik company A.
        $content = $this->actingAs($this->otherCompany)
            ->get(route('company.projects.index'))
            ->assertOk()
            ->getContent();

        $this->assertStringNotContainsString(
            'data-nego-unread-project="' . $this->projectA->id . '"',
            $content
        );

        // Dan tidak bisa menandai read notification milik company A.
        $this->actingAs($this->otherCompany)
            ->getJson("/negotiations/{$this->penawaranA->id}")
            ->assertForbidden();

        $this->assertSame(1, Notification::where('type', 'negotiation.message')->where('is_read', false)->count());
    }

    // ─── PENAWARAN: URUTAN & SORT HARGA & RATA-RATA ──────────

    private function seedSortedPenawarans(): array
    {
        // Backdate penawaran dari setUp agar urutan created_at terkendali.
        $this->penawaranA->created_at = now()->subDays(10);
        $this->penawaranA->save();

        // created_at diset langsung ke properti agar urutan terkendali
        // (mass assignment tidak menerima kolom timestamp).
        $old = $this->makePenawaran(3000000, 'Tiga juta.', now()->subDays(3));
        $newest = $this->makePenawaran(1000000, 'Satu juta.', now()->subDay());
        $middle = $this->makePenawaran(2000000, 'Dua juta.', now()->subDays(2));

        return ['old' => $old, 'newest' => $newest, 'middle' => $middle];
    }

    private function makePenawaran(int $harga, string $pesan, $createdAt): Penawaran
    {
        $p = Penawaran::create([
            'project_id'      => $this->projectA->id,
            'freelancer_id'   => User::factory()->create(['role' => 'freelancer'])->id,
            'harga_penawaran' => $harga,
            'estimasi_hari'   => 10,
            'pesan'           => $pesan,
            'status'          => 'Menunggu',
        ]);

        $p->created_at = $createdAt;
        $p->save();

        return $p;
    }

    public function test_penawaran_terbaru_muncul_paling_atas_secara_default(): void
    {
        ['newest' => $newest] = $this->seedSortedPenawarans();

        $content = $this->actingAs($this->company)
            ->get(route('company.projects.show', $this->projectA))
            ->assertOk()
            ->getContent();

        // Nama freelancer unik: gunakan posisi ID penawaran pada markup negosiasi.
        $posNewest = strpos($content, 'data-negosiasi-open="' . $newest->id . '"');

        foreach (Penawaran::where('project_id', $this->projectA->id)->where('id', '!=', $newest->id)->pluck('id') as $otherId) {
            $posOther = strpos($content, 'data-negosiasi-open="' . $otherId . '"');
            $this->assertTrue(
                $posNewest !== false && $posOther !== false && $posNewest < $posOther,
                "Penawaran terbaru ({$newest->id}) harus tampil di atas penawaran ({$otherId})."
            );
        }
    }

    public function test_sort_harga_tertinggi_and_terendah_from_database(): void
    {
        $ids = $this->seedSortedPenawarans();

        // Harga tertinggi: 3.000.000 paling atas.
        $content = $this->actingAs($this->company)
            ->get(route('company.projects.show', array_merge([$this->projectA], ['sort' => 'harga_tertinggi'])))
            ->assertOk()
            ->getContent();

        $posHigh = strpos($content, 'data-negosiasi-open="' . $ids['old']->id . '"');
        $posLow = strpos($content, 'data-negosiasi-open="' . $ids['newest']->id . '"');
        $this->assertTrue($posHigh !== false && $posLow !== false && $posHigh < $posLow);

        // Harga terendah: 1.000.000 paling atas.
        $content = $this->actingAs($this->company)
            ->get(route('company.projects.show', array_merge([$this->projectA], ['sort' => 'harga_terendah'])))
            ->assertOk()
            ->getContent();

        $posLow2 = strpos($content, 'data-negosiasi-open="' . $ids['newest']->id . '"');
        $posHigh2 = strpos($content, 'data-negosiasi-open="' . $ids['old']->id . '"');
        $this->assertTrue($posLow2 !== false && $posHigh2 !== false && $posLow2 < $posHigh2);
    }

    public function test_average_price_displayed_from_server_calculation(): void
    {
        $this->seedSortedPenawarans();

        // PenawaranA asli 1.000.000 + 3jt/1jt/2jt → rata-rata 1.750.000.
        $this->actingAs($this->company)
            ->get(route('company.projects.show', $this->projectA))
            ->assertOk()
            ->assertSee('Rata-rata: Rp 1.750.000', false);

        // Rata-rata TIDAK disimpan sebagai harga penawaran baru.
        $this->assertFalse(
            Penawaran::where('project_id', $this->projectA->id)->where('harga_penawaran', 1750000)->exists()
        );
    }

    // ─── INPUT HARGA: BACKEND VALIDASI ───────────────────────

    public function test_backend_rejects_non_numeric_proposed_price(): void
    {
        $this->actingAs($this->company)
            ->postJson("/negotiations/{$this->penawaranA->id}/send", [
                'message'        => 'tawaran',
                'proposed_price' => 'satu juta',
            ])
            ->assertStatus(422);

        $this->actingAs($this->company)
            ->postJson("/negotiations/{$this->penawaranA->id}/send", [
                'message'        => 'tawaran',
                // Format ribuan dengan titik ganda bukan numeric valid → harus ditolak backend.
                'proposed_price' => '1.000.000abc',
            ])
            ->assertStatus(422);
    }

    public function test_backend_accepts_clean_numeric_price_unchanged_after_edit_resubmit(): void
    {
        $this->actingAs($this->company)
            ->postJson("/negotiations/{$this->penawaranA->id}/send", [
                'message'        => 'tawaran awal',
                'proposed_price' => 1000000,
            ])
            ->assertCreated();

        $msg = NegotiationMessage::where('penawaran_id', $this->penawaranA->id)->firstOrFail();
        $this->assertEquals('1000000.00', (string) $msg->proposed_price);

        // Re-submit nilai hasil edit UI ("1.000.000" dinormalisasi JS menjadi 1000000).
        $this->actingAs($this->company)
            ->postJson("/negotiations/{$this->penawaranA->id}/send", [
                'message'        => 'revisi',
                'proposed_price' => 1500000,
            ])
            ->assertCreated();

        $last = NegotiationMessage::where('penawaran_id', $this->penawaranA->id)->latest('id')->firstOrFail();
        $this->assertEquals('1500000.00', (string) $last->proposed_price);
    }
}
