<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TResponse>
 */
class ResponseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'thread_id' => fake()->numberBetween(1, 400),
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'uid' => null,
            'ip' => fake()->ipv4,
            'message' => fake()->text
        ];
    }
}