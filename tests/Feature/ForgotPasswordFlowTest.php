<?php

namespace Tests\Feature;

use App\Models\PasswordResetOtp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ForgotPasswordFlowTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(string $email, string $password = 'password'): User
    {
        return User::factory()->create([
            'email' => $email,
            'password' => Hash::make($password),
        ]);
    }

    public function test_send_otp_creates_session_keys_and_record(): void
    {
        $this->makeUser('forget@example.com', 'oldpass123');

        $res = $this->post('/forgot-password', ['email' => 'forget@example.com']);

        // Must redirect to OTP verification page.
        $res->assertFound();
        $this->assertStringContainsString('/verify-otp', $res->baseResponse->headers->get('Location'));

        // Session must carry the reset keys used later.
        $this->assertNotNull(session('password_reset_otp_id'));
        $this->assertSame(
            session('password_reset_otp_id'),
            session('otp_id'),
            'otp_id and password_reset_otp_id must point to the same record.'
        );
        $this->assertSame('forget@example.com', session('password_reset_email'));
        $this->assertNotNull(session('password_reset_user_id'));

        $this->assertSame(
            1,
            PasswordResetOtp::where('email', 'forget@example.com')->count(),
            'Exactly one OTP record should exist for the email.'
        );

        // The page itself must render without blade/JS errors.
        $page = $this->get('/verify-otp');
        $page->assertStatus(200);

        // REGRESI: keenam input harus memakai nama otp_digit_1..6 (bukan literal
        // otp_digit_$i) supaya browser mengirim field yang dibaca controller.
        $html = $page->getContent();
        for ($i = 1; $i <= 6; $i++) {
            $this->assertStringContainsString('name="otp_digit_' . $i . '"', $html);
        }
        $this->assertStringNotContainsString('name="otp_digit_$i"', $html);
    }

    public function test_verify_valid_otp_redirects_to_reset_password_and_updates_password(): void
    {
        $email = 'forget@example.com';
        $user = $this->makeUser($email, 'oldpass123');

        $otp = '834759';
        $record = PasswordResetOtp::create([
            'user_id' => $user->id,
            'email' => $email,
            'otp_hash' => Hash::make($otp),
            'expires_at' => now()->addMinutes(5),
            'attempts' => 0,
        ]);

        // Simulate the session state left behind by a successful sendOtp.
        $this->withSession([
            'password_reset_otp_id' => $record->id,
            'password_reset_otp_sent_at' => now()->timestamp,
            'password_reset_user_id' => $user->id,
            'password_reset_email' => $email,
            'otp_id' => $record->id,
            'otp_email' => $email,
        ]);

        // Submit the OTP across the 6 digit boxes (as the blade does).
        $res = $this->post('/verify-otp', [
            'otp_digit_1' => '8',
            'otp_digit_2' => '3',
            'otp_digit_3' => '4',
            'otp_digit_4' => '7',
            'otp_digit_5' => '5',
            'otp_digit_6' => '9',
        ]);

        $res->assertFound();

        // Session must be marked verified and carry the reset context over
        // to the following GET /reset-password (Laravel-chain with array session).
        $this->assertTrue(session('password_reset_verified') === true);
        $this->assertSame($record->id, session('password_reset_otp_id'));
        $this->assertSame($user->id, session('password_reset_user_id'));
        $this->assertSame($email, session('password_reset_email'));

        $page = $this->get('/reset-password');
        $page->assertStatus(200);

        // Submit new password.
        $reset = $this->post('/reset-password', [
            'password' => 'newpass123',
            'password_confirmation' => 'newpass123',
        ]);
        $reset->assertFound();

        $user->refresh();
        $this->assertTrue(Hash::check('newpass123', $user->password));
        $this->assertNull(session('password_reset_verified'));

        // OTP record must be invalidated/removed so it cannot be reused.
        $this->assertNull(PasswordResetOtp::find($record->id));
    }

    public function test_wrong_otp_increments_attempts_and_stays_on_verify(): void
    {
        $email = 'forget@example.com';
        $user = $this->makeUser($email);

        $record = PasswordResetOtp::create([
            'user_id' => $user->id,
            'email' => $email,
            'otp_hash' => Hash::make('000000'),
            'expires_at' => now()->addMinutes(5),
            'attempts' => 0,
        ]);

        $this->withSession([
            'password_reset_otp_id' => $record->id,
            'password_reset_otp_sent_at' => now()->timestamp,
            'password_reset_user_id' => $user->id,
            'password_reset_email' => $email,
            'otp_id' => $record->id,
            'otp_email' => $email,
        ]);

        $res = $this->post('/verify-otp', [
            'otp_digit_1' => '1',
            'otp_digit_2' => '2',
            'otp_digit_3' => '3',
            'otp_digit_4' => '4',
            'otp_digit_5' => '5',
            'otp_digit_6' => '6',
        ]);

        $res->assertFound();
        $this->assertNull(session('password_reset_verified'));
        $this->assertSame(1, $record->fresh()->attempts);
    }

    public function test_verify_without_session_redirects_to_forgot(): void
    {
        $res = $this->post('/verify-otp', [
            'otp_digit_1' => '8',
            'otp_digit_2' => '3',
            'otp_digit_3' => '4',
            'otp_digit_4' => '7',
            'otp_digit_5' => '5',
            'otp_digit_6' => '9',
        ]);

        $res->assertFound();
        $this->assertStringContainsString('/forgot-password', $res->baseResponse->headers->get('Location'));
    }
}