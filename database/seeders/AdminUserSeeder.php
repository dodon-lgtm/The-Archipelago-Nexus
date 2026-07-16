<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'admin@archipelagonexus.com';
        $email = Str::lower(trim($email));

        $existing = User::query()->where('email', $email)->exists();
        if ($existing) {
            return;
        }

        User::create([
            'name' => 'Administrator',
            'email' => $email,
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);
    }
}

