<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $id = fake()->unique()->safeEmail();

        return [
            'name' => fake()->firstName().' '.fake()->lastName(),
            'uniqueid' => $id,
            'email' => $id,
            'emails' => random_int(0, 1) ? "$id;{$this->faker->safeEmail()}" : null,
            'active' => true,
        ];
    }
}
