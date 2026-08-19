<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 *
 * Factory ini dipakai oleh test regression (mis. MidtransWebhookTest) untuk
 * menciptakan data proyek dummy. Didefinisikan agar Project::factory()
 * tidak lagi melempar "Class Database\Factories\ProjectFactory not found".
 */
class ProjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'project_name' => fake()->sentence(4, true),
            'project_description' => fake()->paragraph(),
            'budget' => fake()->randomNumber(6, true),
            'deadline' => now()->addDays(7)->toDateString(),
            'skills' => 'PHP, Laravel',
            'status' => Project::STATUS_OPEN,
        ];
    }
}
