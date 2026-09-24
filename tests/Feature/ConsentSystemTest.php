<?php

namespace Tests\Feature;

use App\Models\Policy;
use App\Models\User;
use App\Models\UserConsent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConsentSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed policies
        $this->seed(\Database\Seeders\PolicySeeder::class);
    }

    /** @test */
    public function registration_without_terms_consent_fails()
    {
        $response = $this->post(route('register'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '081234567890',
            'is_company' => false,
            'privacy_accepted' => '1',
            'usage_accepted' => '1',
            // terms_accepted missing
        ]);

        $response->assertSessionHasErrors('terms_accepted');
    }

    /** @test */
    public function registration_without_privacy_consent_fails()
    {
        $response = $this->post(route('register'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '081234567890',
            'is_company' => false,
            'terms_accepted' => '1',
            'usage_accepted' => '1',
            // privacy_accepted missing
        ]);

        $response->assertSessionHasErrors('privacy_accepted');
    }

    /** @test */
    public function registration_without_usage_consent_fails()
    {
        $response = $this->post(route('register'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '081234567890',
            'is_company' => false,
            'terms_accepted' => '1',
            'privacy_accepted' => '1',
            // usage_accepted missing
        ]);

        $response->assertSessionHasErrors('usage_accepted');
    }

    /** @test */
    public function registration_with_all_required_consents_succeeds()
    {
        $response = $this->post(route('register'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '081234567890',
            'is_company' => false,
            'terms_accepted' => '1',
            'privacy_accepted' => '1',
            'usage_accepted' => '1',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success');

        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('freelancer', $user->role);

        // Check consents were saved
        $this->assertEquals(3, $user->consents()->where('is_required', true)->count());

        $termsConsent = $user->consents()->whereHas('policy', fn($q) => $q->where('key', Policy::KEY_TERMS))->first();
        $this->assertNotNull($termsConsent);
        $this->assertTrue($termsConsent->is_required);
        $this->assertEquals('1.0', $termsConsent->policy_version);

        $privacyConsent = $user->consents()->whereHas('policy', fn($q) => $q->where('key', Policy::KEY_PRIVACY))->first();
        $this->assertNotNull($privacyConsent);
        $this->assertTrue($privacyConsent->is_required);

        $usageConsent = $user->consents()->whereHas('policy', fn($q) => $q->where('key', Policy::KEY_USAGE))->first();
        $this->assertNotNull($usageConsent);
        $this->assertTrue($usageConsent->is_required);
    }

    /** @test */
    public function registration_with_marketing_consent_optional()
    {
        $response = $this->post(route('register'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '081234567890',
            'is_company' => false,
            'terms_accepted' => '1',
            'privacy_accepted' => '1',
            'usage_accepted' => '1',
            'marketing_accepted' => '1',
        ]);

        $response->assertRedirect(route('login'));

        $user = User::where('email', 'test@example.com')->first();
        $marketingConsents = $user->consents()->where('is_required', false)->get();
        $this->assertEquals(1, $marketingConsents->count());
    }

    /** @test */
    public function registration_without_marketing_consent_succeeds()
    {
        $response = $this->post(route('register'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '081234567890',
            'is_company' => false,
            'terms_accepted' => '1',
            'privacy_accepted' => '1',
            'usage_accepted' => '1',
            // marketing_accepted not provided
        ]);

        $response->assertRedirect(route('login'));

        $user = User::where('email', 'test@example.com')->first();
        $marketingConsents = $user->consents()->where('is_required', false)->get();
        $this->assertEquals(0, $marketingConsents->count());
    }

    /** @test */
    public function consent_timestamps_are_saved()
    {
        $this->post(route('register'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '081234567890',
            'is_company' => false,
            'terms_accepted' => '1',
            'privacy_accepted' => '1',
            'usage_accepted' => '1',
        ]);

        $user = User::where('email', 'test@example.com')->first();
        $consent = $user->consents()->first();

        $this->assertNotNull($consent->accepted_at);
        $this->assertNotNull($consent->ip_address);
        $this->assertNotNull($consent->user_agent);
    }

    /** @test */
    public function consent_version_is_saved()
    {
        $this->post(route('register'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '081234567890',
            'is_company' => false,
            'terms_accepted' => '1',
            'privacy_accepted' => '1',
            'usage_accepted' => '1',
        ]);

        $user = User::where('email', 'test@example.com')->first();
        $consents = $user->consents()->where('is_required', true)->get();

        foreach ($consents as $consent) {
            $this->assertEquals('1.0', $consent->policy_version);
        }
    }

    /** @test */
    public function existing_user_can_still_login()
    {
        // Create user without consents (simulating old user)
        $user = User::create([
            'name' => 'Old User',
            'email' => 'old@example.com',
            'password' => bcrypt('password123'),
            'role' => 'freelancer',
        ]);

        $response = $this->post(route('login'), [
            'email' => 'old@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('freelancer.dashboard'));
    }

    /** @test */
    public function re_consent_required_when_policy_version_changes()
    {
        // Register user
        $this->post(route('register'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '081234567890',
            'is_company' => false,
            'terms_accepted' => '1',
            'privacy_accepted' => '1',
            'usage_accepted' => '1',
        ]);

        $user = User::where('email', 'test@example.com')->first();

        // Update policy version
        $termsPolicy = Policy::where('key', Policy::KEY_TERMS)->first();
        $termsPolicy->update(['version' => '1.1']);

        // User should now have pending policies
        $this->assertTrue($user->fresh()->getPendingRequiredPolicies()->contains('key', Policy::KEY_TERMS));
    }

    /** @test */
    public function user_can_re_consent_to_new_version()
    {
        // Register user
        $this->post(route('register'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '081234567890',
            'is_company' => false,
            'terms_accepted' => '1',
            'privacy_accepted' => '1',
            'usage_accepted' => '1',
        ]);

        $user = User::where('email', 'test@example.com')->first();

        // Update policy version
        $termsPolicy = Policy::where('key', Policy::KEY_TERMS)->first();
        $termsPolicy->update(['version' => '1.1']);

        // Login
        $this->actingAs($user);

        // Submit re-consent
        $response = $this->post(route('consent.required.store'), [
            "policy_{$termsPolicy->id}" => '1',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Check new consent was saved
        $user->refresh();
        $newConsent = $user->consents()
            ->where('policy_id', $termsPolicy->id)
            ->where('policy_version', '1.1')
            ->first();

        $this->assertNotNull($newConsent);

        // Old consent should still exist
        $oldConsent = $user->consents()
            ->where('policy_id', $termsPolicy->id)
            ->where('policy_version', '1.0')
            ->first();

        $this->assertNotNull($oldConsent);
    }

    /** @test */
    public function roles_freelancer_company_admin_still_work()
    {
        // Test freelancer registration
        $this->post(route('register'), [
            'name' => 'Freelancer User',
            'email' => 'freelancer@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '081234567890',
            'is_company' => false,
            'terms_accepted' => '1',
            'privacy_accepted' => '1',
            'usage_accepted' => '1',
        ]);

        $freelancer = User::where('email', 'freelancer@example.com')->first();
        $this->assertEquals('freelancer', $freelancer->role);

        // Test company registration
        $this->post(route('register'), [
            'name' => 'Company User',
            'email' => 'company@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '081234567890',
            'is_company' => true,
            'company_name' => 'Test Company',
            'company_phone' => '081234567890',
            'company_address' => 'Test Address',
            'terms_accepted' => '1',
            'privacy_accepted' => '1',
            'usage_accepted' => '1',
        ]);

        $company = User::where('email', 'company@example.com')->first();
        $this->assertEquals('company', $company->role);
    }
}