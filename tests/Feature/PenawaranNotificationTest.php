<?php

namespace Tests\Feature;

use App\Models\CompanyAccountRequest;
use App\Models\CompanyProfile;
use App\Models\FreelancerProfile;
use App\Models\Notification;
use App\Models\Penawaran;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class PenawaranNotificationTest extends TestCase
{
    use RefreshDatabase;

    private User $company;
    private User $freelancer;
    private User $otherFreelancer;
    private User $outsiderCompany;
    private Project $project;

    protected function setUp(): void
    {
        parent::setUp();

        $this->company = User::factory()->create(['role' => 'company', 'phone' => '081234567890']);
        CompanyProfile::create([
            'user_id'      => $this->company->id,
            'company_name' => 'PT Uji Coba',
            'location'     => 'Jakarta',
        ]);
        $this->approveCompanyAccount($this->company);

        $this->outsiderCompany = User::factory()->create(['role' => 'company']);
        $this->approveCompanyAccount($this->outsiderCompany);

        $this->freelancer = $this->createCompleteFreelancer('Budi');
        $this->otherFreelancer = $this->createCompleteFreelancer('Andi');

        $this->project = Project::factory()->create([
            'user_id' => $this->company->id,
            'status'  => Project::STATUS_OPEN,
        ]);
    }

    private function approveCompanyAccount(User $company): void
    {
        CompanyAccountRequest::create([
            'company_name'    => 'PT ' . $company->name,
            'contact_person'  => $company->name,
            'company_email'   => $company->email,
            'company_phone'   => $company->phone ?? '080000000000',
            'company_address' => 'Jl. Uji No. 1',
            'request_status'  => 'disetujui',
        ]);
    }

    private function createCompleteFreelancer(string $name): User
    {
        $user = User::factory()->create([
            'role'  => 'freelancer',
            'name'  => $name,
            'phone' => '081234567891',
        ]);

        FreelancerProfile::create([
            'user_id'  => $user->id,
            'location' => 'Bandung',
            'skills'   => 'PHP, Laravel',
        ]);

        return $user;
    }

    private function lamaranPayload(): array
    {
        return [
            'harga_penawaran' => 1500000,
            'estimasi_hari'   => 10,
            'pesan'           => 'Saya tertarik dengan proyek ini.',
            'proposal'        => UploadedFile::fake()->create('proposal.pdf', 200, 'application/pdf'),
        ];
    }

    // ─── FREELANCER → COMPANY ────────────────────────────────

    public function test_freelancer_lamaran_creates_unread_notification_for_company(): void
    {
        $response = $this->actingAs($this->freelancer)
            ->post(route('freelancer.penawaran.store', $this->project), $this->lamaranPayload());

        $response->assertRedirect(route('freelancer.dashboard'));

        $penawaran = Penawaran::where('project_id', $this->project->id)
            ->where('freelancer_id', $this->freelancer->id)
            ->firstOrFail();

        $notification = Notification::where('type', 'offer.sent')->firstOrFail();
        $this->assertSame($this->company->id, $notification->user_id);
        $this->assertSame($this->freelancer->id, $notification->sender_id);
        $this->assertSame($penawaran->id, $notification->penawaran_id);
        $this->assertFalse($notification->is_read);
        $this->assertSame(
            route('company.projects.show', $this->project),
            $notification->data['redirect']
        );

        // Unread count endpoint menghitung notification ini.
        $index = $this->actingAs($this->company)->getJson(route('notifications.index'))->assertOk();
        $this->assertSame(1, $index->json('unread_count'));
        $this->assertFalse((bool) $index->json('notifications.0.is_read'));
    }

    public function test_duplicate_lamaran_does_not_create_duplicate_notification(): void
    {
        $this->actingAs($this->freelancer)
            ->post(route('freelancer.penawaran.store', $this->project), $this->lamaranPayload())
            ->assertRedirect();

        // Retry / double submit → ditolak karena sudah pernah mengirim.
        $this->actingAs($this->freelancer)
            ->post(route('freelancer.penawaran.store', $this->project), $this->lamaranPayload())
            ->assertRedirect(route('freelancer.projects.show', $this->project))
            ->assertSessionHas('error');

        $this->assertSame(1, Penawaran::where('project_id', $this->project->id)
            ->where('freelancer_id', $this->freelancer->id)->count());
        $this->assertSame(1, Notification::where('type', 'offer.sent')->count());
    }

    public function test_lamaran_click_mark_read_reduces_badge_and_redirects(): void
    {
        $this->actingAs($this->freelancer)
            ->post(route('freelancer.penawaran.store', $this->project), $this->lamaranPayload())
            ->assertRedirect();

        $notif = Notification::where('type', 'offer.sent')->firstOrFail();

        // Bell/toast click → mark-read (mekanisme existing) → badge berkurang → redirect.
        $this->actingAs($this->company)
            ->postJson("/notifications/{$notif->id}/read")
            ->assertOk()
            ->assertJsonPath('unread_count', 0)
            ->assertJsonPath('redirect_url', route('company.projects.show', $this->project));

        $this->assertTrue($notif->fresh()->is_read);
    }

    public function test_outsider_cannot_send_lamaran_for_other_context(): void
    {
        // User yang belum lengkap profilnya tidak dapat mengirim lamaran
        // sehingga tidak ada notification yang dibuat.
        $incomplete = User::factory()->create(['role' => 'freelancer']);

        $this->actingAs($incomplete)
            ->post(route('freelancer.penawaran.store', $this->project), $this->lamaranPayload())
            ->assertRedirect(route('freelancer.profile'))
            ->assertSessionHas('error');

        $this->assertSame(0, Notification::count());
    }

    public function test_guest_cannot_send_lamaran(): void
    {
        // Request terpisah tanpa sesi login (auth middleware → redirect ke /login).
        $this->post(route('freelancer.penawaran.store', $this->project), $this->lamaranPayload())
            ->assertRedirect(route('login'));

        $this->assertSame(0, Notification::count());
    }

    // ─── COMPANY → FREELANCER ────────────────────────────────

    private function createLamaran(User $freelancer): Penawaran
    {
        return Penawaran::create([
            'project_id'      => $this->project->id,
            'freelancer_id'   => $freelancer->id,
            'harga_penawaran' => 2000000,
            'estimasi_hari'   => 14,
            'pesan'           => 'Lamaran saya.',
            'status'          => 'Menunggu',
        ]);
    }

    public function test_selecting_freelancer_notifies_chosen_and_rejected_freelancers(): void
    {
        $chosen = $this->createLamaran($this->freelancer);
        $rejected = $this->createLamaran($this->otherFreelancer);

        $this->actingAs($this->company)
            ->post(route('company.projects.penawaran.select', [$this->project, $chosen]))
            ->assertRedirect();

        // Freelancer terpilih → offer.accepted (unread).
        $accepted = Notification::where('type', 'offer.accepted')->firstOrFail();
        $this->assertSame($this->freelancer->id, $accepted->user_id);
        $this->assertSame($this->company->id, $accepted->sender_id);
        $this->assertSame($chosen->id, $accepted->penawaran_id);
        $this->assertFalse($accepted->is_read);

        // Freelancer lain → offer.rejected (unread).
        $rejectedNotif = Notification::where('type', 'offer.rejected')->firstOrFail();
        $this->assertSame($this->otherFreelancer->id, $rejectedNotif->user_id);
        $this->assertSame($rejected->id, $rejectedNotif->penawaran_id);
        $this->assertFalse($rejectedNotif->is_read);

        // Redirect masing-masing valid.
        $workspace = $this->project->workspace;
        $this->assertSame(
            route('freelancer.workspaces.show', ['workspace' => $workspace->id]),
            str_replace('/workspaces/', '/workspaces/', $accepted->data['redirect'])
        );
        $this->assertSame(
            route('freelancer.projects.show', $this->project),
            $rejectedNotif->data['redirect']
        );
    }

    public function test_selected_freelancer_mark_read_flow_works(): void
    {
        $chosen = $this->createLamaran($this->freelancer);

        $this->actingAs($this->company)
            ->post(route('company.projects.penawaran.select', [$this->project, $chosen]))
            ->assertRedirect();

        $notif = Notification::where('type', 'offer.accepted')->firstOrFail();

        $index = $this->actingAs($this->freelancer)->getJson(route('notifications.index'))->assertOk();
        $this->assertSame(1, $index->json('unread_count'));

        // Klik notification → read → unread count aktual dari database.
        $this->actingAs($this->freelancer)
            ->postJson("/notifications/{$notif->id}/read")
            ->assertOk()
            ->assertJsonPath('unread_count', 0);

        $this->assertTrue($notif->fresh()->is_read);
    }

    public function test_other_company_cannot_select_penawaran_on_foreign_project(): void
    {
        $chosen = $this->createLamaran($this->freelancer);

        $this->actingAs($this->outsiderCompany)
            ->post(route('company.projects.penawaran.select', [$this->project, $chosen]))
            ->assertForbidden();

        $this->assertSame(0, Notification::count());
        $this->assertSame('Menunggu', $chosen->fresh()->status);
    }

    public function test_freelancer_cannot_access_company_select_route(): void
    {
        $chosen = $this->createLamaran($this->freelancer);

        $this->actingAs($this->freelancer)
            ->post(route('company.projects.penawaran.select', [$this->project, $chosen]))
            ->assertForbidden();

        $this->assertSame(0, Notification::count());
    }
}
