<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Projects>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'description' => fake()->sentence(4),
            'owner_id' => User::factory(),
        ];
    }

    public function ownedBy(User $user)
    {
        return $this->state(fn(array $attributes) => [
            'owner_id' => $user,
        ]);
    }
}
