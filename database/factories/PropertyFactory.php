<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class PropertyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'price' => $this->faker->numberBetween(100000, 100000000),
            'frequency' => $this->faker->randomElement(['month', 'night', 'total']),
            'transaction_type' => $this->faker->randomElement(['rent', 'sale']),
            'property_type' => $this->faker->randomElement(['studio', 'villa', 'apartment']),
            'city' => $this->faker->city,
            'neighborhood' => $this->faker->streetName,
            'bedrooms' => $this->faker->numberBetween(1, 5),
            'bathrooms' => $this->faker->numberBetween(1, 3),
            'area' => $this->faker->numberBetween(20, 500),
            'status' => 'published',
        ];
    }
}
