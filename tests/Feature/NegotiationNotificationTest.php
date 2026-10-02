<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\Penawaran;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NegotiationNotificationTest extends TestCase
{
    use RefreshDatabase;

    private User $company;
    private User $freelancer;
    private User $outsider;
    private Project $project;
    private Penawaran $penawaran;

    protected function setUp(): void
    {
        parent::setUp();

        $this->company    = User::factory()->create(['role' => 'company', 'name' => 'PT Maju']);
        $this->freelancer = User::factory()->create(['role' => 'freelancer', 'name' => 'Budi']);
        $this->outsider   = User::factory()->create(['role' => 'freelancer', 'name' => 'Outsider']);

        $this->project = Project::factory()->create([
            'user_id'      => $this->company->id,
            'project_name' => 'Proyek Uji Negosiasi',
        ]);

        $this->penawaran = Penawaran::create([
            'project_id'      => $this->project->id,
            'freelancer_id'   => $this->freelancer->id,
            'harga_penawaran' => 1000000,
            'estimasi_hari'   => 7,
            'pesan'           => 'Saya tertarik mengerjakan proyek ini.',
            'status'          => 'Menunggu',
        ]);
    }

    public function test_company_message_creates_notification_for_freelancer(): void
    {
        $this->actingAs($this->company)
            ->postJson("/negotiations/{$this->penawaran->id}/send", [
                'message' => 'Apakah harga bisa dinegosiasikan?',
            ])
            ->assertCreated()
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('negotiation_messages', [
            'penawaran_id' => $this->penawaran->id,
            'sender_id'    => $this->company->id,
            'sender_type'  => 'company',
        ]);

        $this->assertDatabaseHas('notifications', [
            'user_id'       => $this->freelancer->id,
            'sender_id'     => $this->company->id,
            'type'          => 'negotiation.message',
            'title'         => 'Pesan Negosiasi Baru',
            'penawaran_id'  => $this->penawaran->id,
            'project_id'    => $this->project->id,
            'message'       => 'PT Maju mengirim pesan negosiasi untuk proyek "Proyek Uji Negosiasi".',
        ]);

        $this->assertSame(1, Notification::where('user_id', $this->freelancer->id)->count());
    }

    public function test_freelancer_message_creates_notification_for_company(): void
    {
        $this->actingAs($this->freelancer)
            ->postJson("/negotiations/{$this->penawaran->id}/send", [
                'message' => 'Baik, saya setuju dengan penawarannya.',
            ])
            ->assertCreated()
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('negotiation_messages', [
            'penawaran_id' => $this->penawaran->id,
            'sender_id'    => $this->freelancer->id,
            'sender_type'  => 'freelancer',
        ]);

        $this->assertDatabaseHas('notifications', [
            'user_id'       => $this->company->id,
            'sender_id'     => $this->freelancer->id,
            'type'          => 'negotiation.message',
            'title'         => 'Pesan Negosiasi Baru',
            'penawaran_id'  => $this->penawaran->id,
            'project_id'    => $this->project->id,
            'message'       => 'Budi mengirim pesan negosiasi untuk proyek "Proyek Uji Negosiasi".',
        ]);

        $this->assertSame(1, Notification::where('user_id', $this->company->id)->count());
    }

    public function test_notification_recipient_is_counterparty_not_sender(): void
    {
        // Company mengirim → freelancer yang menerima, company TIDAK menerima.
        $this->actingAs($this->company)
            ->postJson("/negotiations/{$this->penawaran->id}/send", ['message' => 'Halo'])
            ->assertCreated();

        $this->assertSame(0, Notification::where('user_id', $this->company->id)->count());
        $this->assertSame(1, Notification::where('user_id', $this->freelancer->id)->count());

        // Freelancer membalas → company menerima, freelancer TIDAK mendapat notifikasi baru.
        $this->actingAs($this->freelancer)
            ->postJson("/negotiations/{$this->penawaran->id}/send", ['message' => 'Halo juga'])
            ->assertCreated();

        $this->assertSame(1, Notification::where('user_id', $this->company->id)->count());
        $this->assertSame(1, Notification::where('user_id', $this->freelancer->id)->count());

        foreach (Notification::all() as $notification) {
            $this->assertNotSame($notification->user_id, $notification->sender_id);
        }
    }

    public function test_notification_redirect_points_to_negotiation_context(): void
    {
        // Company → Freelancer: redirect ke halaman Lamaran Saya.
        $this->actingAs($this->company)
            ->postJson("/negotiations/{$this->penawaran->id}/send", ['message' => 'Tawaran baru'])
            ->assertCreated();

        $notif = Notification::where('user_id', $this->freelancer->id)->firstOrFail();
        $this->assertSame(route('freelancer.lamaran'), $notif->data['redirect']);

        // Freelancer → Company: redirect ke detail proyek company.
        $this->actingAs($this->freelancer)
            ->postJson("/negotiations/{$this->penawaran->id}/send", ['message' => 'Setuju'])
            ->assertCreated();

        $notif = Notification::where('user_id', $this->company->id)->firstOrFail();
        $this->assertSame(route('company.projects.show', $this->project), $notif->data['redirect']);

        // markRead endpoint tetap bekerja dan mengembalikan redirect_url.
        $this->actingAs($this->company)
            ->postJson("/notifications/{$notif->id}/read")
            ->assertOk()
            ->assertJsonPath('redirect_url', route('company.projects.show', $this->project));

        $this->assertTrue($notif->fresh()->is_read);
    }

    public function test_unauthorized_user_cannot_send_and_no_notification_created(): void
    {
        $this->actingAs($this->outsider)
            ->postJson("/negotiations/{$this->penawaran->id}/send", ['message' => 'Spam'])
            ->assertForbidden();

        $this->assertSame(0, Notification::count());

        // Outsider tidak dapat menandai notifikasi milik orang lain.
        $notif = Notification::create([
            'user_id'  => $this->company->id,
            'sender_id'=> $this->freelancer->id,
            'type'     => 'negotiation.message',
            'title'    => 'Pesan Negosiasi Baru',
            'message'  => 'Budi mengirim pesan negosiasi untuk proyek "Proyek Uji Negosiasi".',
        ]);

        $this->actingAs($this->outsider)
            ->postJson("/notifications/{$notif->id}/read")
            ->assertForbidden();

        $this->assertFalse($notif->fresh()->is_read);
    }

    public function test_unread_count_endpoint_reflects_negotiation_notification(): void
    {
        $this->actingAs($this->company)
            ->postJson("/negotiations/{$this->penawaran->id}/send", ['message' => 'Halo'])
            ->assertCreated();

        $response = $this->actingAs($this->freelancer)->getJson(route('notifications.index'))->assertOk();

        $this->assertSame(1, $response->json('unread_count'));
        $this->assertSame(
            route('freelancer.lamaran'),
            $response->json('notifications.0.data.redirect')
        );
    }
}
