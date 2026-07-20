<?php

namespace Database\Seeders;

use App\Models\CompanyAccountRequest;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        // ── Company User (already approved) ──
        $companyEmail = Str::lower(trim('company@archipelagonexus.com'));

        /** @var User|null $companyUser */
        $companyUser = User::query()->where('email', $companyEmail)->first();

        if (!$companyUser) {
            $companyUser = User::create([
                'name' => 'Company Testing',
                'email' => $companyEmail,
                'password' => Hash::make('company123'),
                'role' => 'company',
            ]);
        }

        // Create or update the approved company account request
        CompanyAccountRequest::query()->updateOrCreate(
            ['company_email' => $companyEmail],
            [
                'company_name' => 'Archipelago Tech Corp',
                'contact_person' => 'Company Testing',
                'company_phone' => '081234567890',
                'company_address' => 'Jl. Merdeka No. 123, Jakarta Pusat',
                'company_description' => 'Perusahaan teknologi yang bergerak di bidang pengembangan web dan mobile.',
                'request_status' => 'disetujui',
                'reviewed_by' => User::query()->where('role', 'admin')->first()?->id,
                'note' => 'Seeder: akun company untuk testing.',
            ]
        );

        // ── Freelancer User ──
        $freelancerEmail = Str::lower(trim('freelancer@archipelagonexus.com'));

        $existingFreelancer = User::query()->where('email', $freelancerEmail)->exists();

        if (!$existingFreelancer) {
            User::create([
                'name' => 'Freelancer Testing',
                'email' => $freelancerEmail,
                'password' => Hash::make('freelancer123'),
                'role' => 'freelancer',
            ]);
        }
    }
}

