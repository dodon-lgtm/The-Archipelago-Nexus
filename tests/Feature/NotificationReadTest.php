<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\Penawaran;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationReadTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private User $other;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user  = User::factory()->create(['role' => 'freelancer']);
        $this->other = User::factory()->create(['role' => 'company']);
    }

    private function createNotification(array $overrides = []): Notification
    {
        return Notification::create(array_merge([
            'user_id'  => $this->user->id,
            'sender_id'=> $this->other->id,
            'type'     => 'negotiation.message',
            'title'    => 'Pesan Negosiasi Baru',
            'message'  => 'Pengirim mengirim pesan negosiasi untuk proyek "Proyek".',
            'data'     => ['redirect' => url('/freelancer/lamaran')],
            'is_read'  => false,
        ], $overrides));
    }

    public function test_clicking_single_unread_notification_marks_it_read(): void
    {
        $notif = $this->createNotification();

        $this->actingAs($this->user)
            ->postJson("/notifications/{$notif->id}/read")
            ->assertOk();

        $this->assertTrue($notif->fresh()->is_read);
        $this->assertNotNull($notif->fresh()->read_at);

        $index = $this->actingAs($this->user)->getJson(route('notifications.index'))->assertOk();
        $this->assertSame(0, $index->json('unread_count'));
    }

    public function test_reading_one_notification_keeps_others_unread(): void
    {
        $a = $this->createNotification(['message' => 'A']);
        $b = $this->createNotification(['message' => 'B']);
        $c = $this->createNotification(['message' => 'C']);

        // Klik notification A saja.
        $response = $this->actingAs($this->user)
            ->postJson("/notifications/{$a->id}/read")
            ->assertOk();

        // Response endpoint memuat unread_count aktual.
        $this->assertSame(2, $response->json('unread_count'));

        $this->assertTrue($a->fresh()->is_read);
        $this->assertFalse($b->fresh()->is_read);
        $this->assertFalse($c->fresh()->is_read);

        $index = $this->actingAs($this->user)->getJson(route('notifications.index'))->assertOk();
        $this->assertSame(2, $index->json('unread_count'));
    }

    public function test_mark_read_returns_redirect_from_data_redirect(): void
    {
        $notif = $this->createNotification();

        $this->actingAs($this->user)
            ->postJson("/notifications/{$notif->id}/read")
            ->assertOk()
            ->assertJsonPath('redirect_url', url('/freelancer/lamaran'))
            ->assertJsonPath('unread_count', 0);
    }

    public function test_new_notification_without_click_stays_unread(): void
    {
        // Notifikasi baru dibuat (misal toast muncul) tanpa diklik.
        $notif = $this->createNotification();

        $index = $this->actingAs($this->user)->getJson(route('notifications.index'))->assertOk();

        $this->assertSame(1, $index->json('unread_count'));
        $this->assertFalse((bool) $index->json('notifications.0.is_read'));
        $this->assertSame($notif->id, $index->json('notifications.0.id'));
    }

    public function test_mark_all_read_clears_everything(): void
    {
        $this->createNotification(['message' => 'A']);
        $this->createNotification(['message' => 'B']);
        $this->createNotification(['message' => 'C']);

        $this->actingAs($this->user)
            ->postJson(route('notifications.mark-all-read'))
            ->assertOk()
            ->assertJson(['success' => true]);

        $index = $this->actingAs($this->user)->getJson(route('notifications.index'))->assertOk();
        $this->assertSame(0, $index->json('unread_count'));
        $this->assertSame(0, Notification::where('user_id', $this->user->id)->where('is_read', false)->count());
    }

    public function test_user_cannot_mark_other_users_notification(): void
    {
        $notif = $this->createNotification(); // milik $this->user

        $this->actingAs($this->other)
            ->postJson("/notifications/{$notif->id}/read")
            ->assertForbidden();

        $this->assertFalse($notif->fresh()->is_read);

        // mark-all-read user lain tidak mempengaruhi notifikasi milik $this->user.
        $this->actingAs($this->other)->postJson(route('notifications.mark-all-read'))->assertOk();
        $this->assertFalse($notif->fresh()->is_read);
    }
}
