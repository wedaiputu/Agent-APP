<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class VoucherFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition()
    {
        return [
            'username' => $this->faker->unique()->userName(),
            'password' => $this->faker->password(),
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'limit_uptime' => $this->faker->randomElement(['1d', '7d', '30d']),
            'price' => $this->faker->numberBetween(50, 500),
            'profile' => $this->faker->randomElement(['basic', 'premium', 'unlimited']),
            'comment' => $this->faker->sentence(),
        ];
    }
}
