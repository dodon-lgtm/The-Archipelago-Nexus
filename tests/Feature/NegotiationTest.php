<?php

namespace Tests\Feature;

use App\Models\NegotiationMessage;
use App\Models\Penawaran;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NegotiationTest extends TestCase
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

        $this->company    = User::factory()->create(['role' => 'company']);
        $this->freelancer = User::factory()->create(['role' => 'freelancer']);
        $this->outsider   = User::factory()->create(['role' => 'freelancer']);

        $this->project = Project::factory()->create([
            'user_id' => $this->company->id,
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

    public function test_guest_cannot_access_negotiation(): void
    {
        // Request JSON → middleware auth mengembalikan 401, bukan redirect.
        $this->getJson("/negotiations/{$this->penawaran->id}")
            ->assertUnauthorized();
    }

    public function test_unauthorized_user_gets_403(): void
    {
        $this->actingAs($this->outsider)
            ->getJson("/negotiations/{$this->penawaran->id}")
            ->assertForbidden();
    }

    public function test_freelancer_can_get_messages(): void
    {
        NegotiationMessage::create([
            'penawaran_id' => $this->penawaran->id,
            'sender_id'    => $this->company->id,
            'sender_type'  => 'company',
            'message'      => 'Apakah harga bisa turun?',
        ]);

        $this->actingAs($this->freelancer)
            ->getJson("/negotiations/{$this->penawaran->id}")
            ->assertOk()
            ->assertJsonStructure([
                'success', 'messages' => [['id', 'message', 'sender_type', 'sender_name', 'proposed_price', 'proposed_days', 'status', 'is_mine', 'created_at']],
                'penawaran' => ['id', 'harga_penawaran', 'estimasi_hari', 'status'],
            ])
            ->assertJsonCount(1, 'messages');
    }

    public function test_company_can_send_offer_with_price_and_days_pending(): void
    {
        $response = $this->actingAs($this->company)
            ->postJson("/negotiations/{$this->penawaran->id}/send", [
                'message'        => 'Bagaimana jika harga 900.000 dan selesai dalam 5 hari?',
                'proposed_price' => 900000,
                'proposed_days'  => 5,
            ])
            ->assertCreated()
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('negotiation_messages', [
            'penawaran_id'  => $this->penawaran->id,
            'sender_id'     => $this->company->id,
            'sender_type'   => 'company',
            'proposed_price' => 900000,
            'proposed_days'  => 5,
            'status'        => NegotiationMessage::STATUS_PENDING,
        ]);

        $payload = $response->json('message');
        $this->assertTrue($payload['is_mine']);
        $this->assertSame('company', $payload['sender_type']);

        // Mengirim tawaran BELUM mengubah penawaran utama (menunggu persetujuan).
        $this->assertDatabaseHas('penawarans', [
            'id'              => $this->penawaran->id,
            'harga_penawaran' => 1000000,
            'estimasi_hari'   => 7,
        ]);
    }

    public function test_freelancer_cannot_propose_price_or_days(): void
    {
        $this->actingAs($this->freelancer)
            ->postJson("/negotiations/{$this->penawaran->id}/send", [
                'message'        => 'Baik, saya setuju. Terima kasih!',
                'proposed_price' => 999999,
                'proposed_days'  => 3,
            ])
            ->assertCreated();

        // Field tawaran diabaikan untuk pengirim selain perusahaan.
        $this->assertDatabaseHas('negotiation_messages', [
            'penawaran_id'  => $this->penawaran->id,
            'sender_type'   => 'freelancer',
            'proposed_price' => null,
            'proposed_days'  => null,
        ]);
    }

    public function test_send_message_requires_message_field(): void
    {
        $this->actingAs($this->company)
            ->postJson("/negotiations/{$this->penawaran->id}/send", [
                'message' => '',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('message');
    }

    public function test_freelancer_accept_offer_updates_penawaran(): void
    {
        $offer = NegotiationMessage::create([
            'penawaran_id'   => $this->penawaran->id,
            'sender_id'      => $this->company->id,
            'sender_type'    => 'company',
            'message'        => 'Tawaran: 900.000 dalam 5 hari.',
            'proposed_price' => 900000,
            'proposed_days'  => 5,
            'status'         => NegotiationMessage::STATUS_PENDING,
        ]);

        $this->actingAs($this->freelancer)
            ->postJson("/negotiations/{$this->penawaran->id}/{$offer->id}/accept")
            ->assertOk()
            ->assertJsonPath('message.status', NegotiationMessage::STATUS_ACCEPTED);

        $this->assertDatabaseHas('negotiation_messages', [
            'id'     => $offer->id,
            'status' => NegotiationMessage::STATUS_ACCEPTED,
        ]);

        // Harga & durasi penawaran utama diperbarui sesuai tawaran disetujui.
        $this->assertDatabaseHas('penawarans', [
            'id'              => $this->penawaran->id,
            'harga_penawaran' => 900000,
            'estimasi_hari'   => 5,
        ]);
    }

    public function test_freelancer_reject_offer_marks_rejected_without_reason(): void
    {
        $offer = NegotiationMessage::create([
            'penawaran_id'   => $this->penawaran->id,
            'sender_id'      => $this->company->id,
            'sender_type'    => 'company',
            'message'        => 'Tawaran: 800.000 dalam 10 hari.',
            'proposed_price' => 800000,
            'proposed_days'  => 10,
            'status'         => NegotiationMessage::STATUS_PENDING,
        ]);

        $this->actingAs($this->freelancer)
            ->postJson("/negotiations/{$this->penawaran->id}/{$offer->id}/reject")
            ->assertOk()
            ->assertJsonPath('message.status', NegotiationMessage::STATUS_REJECTED);

        $this->assertDatabaseHas('negotiation_messages', [
            'id'     => $offer->id,
            'status' => NegotiationMessage::STATUS_REJECTED,
        ]);

        // Penolakan TIDAK mengubah penawaran utama.
        $this->assertDatabaseHas('penawarans', [
            'id'              => $this->penawaran->id,
            'harga_penawaran' => 1000000,
            'estimasi_hari'   => 7,
        ]);
    }

    public function test_company_cannot_accept_own_offer(): void
    {
        $offer = NegotiationMessage::create([
            'penawaran_id'   => $this->penawaran->id,
            'sender_id'      => $this->company->id,
            'sender_type'    => 'company',
            'message'        => 'Tawaran: 900.000.',
            'proposed_price' => 900000,
            'status'         => NegotiationMessage::STATUS_PENDING,
        ]);

        $this->actingAs($this->company)
            ->postJson("/negotiations/{$this->penawaran->id}/{$offer->id}/accept")
            ->assertForbidden();
    }

    public function test_offer_cannot_be_responded_twice(): void
    {
        $offer = NegotiationMessage::create([
            'penawaran_id'   => $this->penawaran->id,
            'sender_id'      => $this->company->id,
            'sender_type'    => 'company',
            'message'        => 'Tawaran: 900.000.',
            'proposed_price' => 900000,
            'status'         => NegotiationMessage::STATUS_ACCEPTED,
        ]);

        $this->actingAs($this->freelancer)
            ->postJson("/negotiations/{$this->penawaran->id}/{$offer->id}/accept")
            ->assertStatus(422);
    }
}