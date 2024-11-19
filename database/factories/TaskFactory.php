<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Template;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::inRandomOrder()->first();

        return [
            'title' => fake()->realText(15),
            'description' => fake()->realText(15),
            'user_id' => $user ? $user->id : User::factory(),
            'template_id' => fake()->boolean(50) ? Template::inRandomOrder()->first()->id : null
        ];
    }
}
