<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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

        $day = str_pad((string) fake()->numberBetween(1, 28), 2, '0', STR_PAD_LEFT);
        $month = str_pad((string) fake()->numberBetween(1, 12), 2, '0', STR_PAD_LEFT);
        $year = (string) fake()->numberBetween(1900, 2022);
        $dateOfBirth = $day . '/' . $month . '/' . $year;

        return [
            'name' => fake()->firstName(),
            'surname' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'phone' => 6 . fake()->randomNumber(8, true),
            'address' => fake()->streetAddress(),
            'date_of_birth' => $dateOfBirth,
            'collegiate_number' => fake()->randomNumber(6, true),
            'role_id' => fake()->randomElement(range(1, 2)),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
