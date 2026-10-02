<?php

namespace Tests\Feature;

use App\Models\CompanyAccountRequest;
use App\Models\CompanyProfile;
use App\Models\FinancialSetting;
use App\Models\Project;
use App\Models\User;
use App\Services\ProjectQuotaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * ProjectQuotaSettingsTest — limit upload gratis dari Financial Settings.
 */
class ProjectQuotaSettingsTest extends TestCase
{
    use RefreshDatabase;

    private function setSettings(int $freeUploads): void
    {
        FinancialSetting::query()->delete();
        FinancialSetting::create([
            'project_fee_rate' => 5,
            'withdrawal_fee_rate' => 5,
            'free_project_uploads_per_month' => $freeUploads,
            'paid_project_upload_price' => 10000,
        ]);
    }

    /** Company dengan profil lengkap (≥80%) dan akun disetujui admin. */
    private function completeCompany(string $name = 'PT Uji Coba'): User
    {
        $company = User::factory()->create([
            'role'  => 'company',
            'phone' => '081234567890',
        ]);

        CompanyProfile::create([
            'user_id'      => $company->id,
            'company_name' => $name,
            'location'     => 'Jakarta',
        ]);

        CompanyAccountRequest::create([
            'company_name'   => $name,
            'contact_person' => $company->name,
            'company_email'  => $company->email,
            'company_phone'  => '081234567890',
            'company_address' => 'Jl. Uji Coba No. 1, Jakarta',
            'request_status' => 'disetujui',
        ]);

        return $company;
    }

    private function projectPayload(): array
    {
        return [
            'project_name'        => 'Proyek Uji Kuota',
            'project_description' => 'Deskripsi proyek uji kuota.',
            'budget'              => 1000000,
            'deadline'            => now()->addDays(7)->toDateString(),
            'skills'              => 'PHP, Laravel',
            'status'              => Project::STATUS_OPEN,
        ];
    }

    private function storeProject(User $company)
    {
        return $this->actingAs($company)
            ->post(route('company.projects.store'), $this->projectPayload());
    }

    public function test_limit_3_allows_three_then_blocks_fourth(): void
    {
        $this->setSettings(3);
        $service = app(ProjectQuotaService::class);
        $company = $this->completeCompany();

        // Upload #1–#3 masih dalam kuota gratis.
        for ($i = 1; $i <= 3; $i++) {
            $this->storeProject($company)->assertRedirect(route('company.dashboard'));
        }

        $this->assertSame(3, Project::where('user_id', $company->id)->count());

        // Upload ke-4 → DIBLOKIR & diarahkan ke pembayaran kuota.
        $blocked = $this->storeProject($company);
        $blocked->assertRedirect(route('company.projects.create'));
        $blocked->assertSessionHas('quota_payment_id');

        $this->assertSame(3, Project::where('user_id', $company->id)->count());
        $this->assertFalse($service->canCreateProject($company->id));
    }

    public function test_admin_can_raise_limit_to_5(): void
    {
        $service = app(ProjectQuotaService::class);
        $company = $this->completeCompany();

        Project::factory()->count(3)->create(['user_id' => $company->id]);
        $this->assertFalse($service->canCreateProject($company->id));

        // Admin mengubah limit 3 → 5.
        $this->setSettings(5);
        $this->assertSame(5, $service->freeQuota());
        $this->assertTrue($service->canCreateProject($company->id));

        // #4 dan #5 kini masih dalam kuota gratis (tanpa pembayaran).
        for ($i = 1; $i <= 2; $i++) {
            $this->storeProject($company)->assertRedirect(route('company.dashboard'));
        }
        $this->assertSame(5, Project::where('user_id', $company->id)->count());

        // #6 terblokir → diarahkan ke gateway kuota.
        $blocked = $this->storeProject($company);
        $blocked->assertRedirect(route('company.projects.create'));
        $blocked->assertSessionHas('quota_payment_id');

        $this->assertSame(5, Project::where('user_id', $company->id)->count());
    }

    public function test_other_company_quota_is_isolated(): void
    {
        $this->setSettings(3);
        $service = app(ProjectQuotaService::class);

        $a = $this->completeCompany('PT A');
        $b = $this->completeCompany('PT B');

        Project::factory()->count(3)->create(['user_id' => $a->id]);
        $this->assertFalse($service->canCreateProject($a->id));

        // Company B belum membuat apa pun → tetap boleh.
        $this->assertTrue($service->canCreateProject($b->id));
        $this->storeProject($b)->assertRedirect(route('company.dashboard'));

        // Penambahan project B tidak mengubah status kuota A.
        $this->assertFalse($service->canCreateProject($a->id));
        $this->assertSame(1, Project::where('user_id', $b->id)->count());
    }

    public function test_usage_resets_on_next_calendar_month(): void
    {
        $this->setSettings(3);
        $service = app(ProjectQuotaService::class);
        $company = $this->completeCompany();

        Project::factory()->count(3)->create(['user_id' => $company->id]);
        $this->assertFalse($service->canCreateProject($company->id));

        // Geser created_at proyek-proyek ke bulan sebelumnya (reset kuota).
        foreach (Project::where('user_id', $company->id)->get() as $project) {
            $project->forceFill(['created_at' => now()->subMonth()])->saveQuietly();
        }

        $this->assertTrue($service->canCreateProject($company->id));
        $this->storeProject($company)->assertRedirect(route('company.dashboard'));
        $this->assertSame(1, Project::where('user_id', $company->id)
            ->where('created_at', '>=', now()->startOfMonth())->count());
    }
}
