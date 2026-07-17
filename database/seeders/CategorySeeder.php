<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Web Development',
            'Mobile Development',
            'UI/UX Design',
            'Graphic Design',
            'Digital Marketing',
            'Data Entry',
            'Content Writing',
        ];

        foreach ($categories as $name) {
            Category::query()->firstOrCreate([
                'name' => $name,
            ]);
        }
    }
}

