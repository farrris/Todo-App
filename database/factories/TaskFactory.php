<?php

namespace Database\Factories;

use App\Enums\TaskStatusEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        return [
            "title" => fake()->title(),
            "description" => fake()->text(),
            "user_id" => User::factory(),
            "status" => TaskStatusEnum::Todo->value
        ];
    }
}
