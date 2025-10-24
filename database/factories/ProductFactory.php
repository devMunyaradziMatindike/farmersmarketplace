<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'name' => fake()->words(3, true),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 10, 1000),
            'location' => fake()->city().', Zimbabwe',
            'latitude' => fake()->latitude(-22, -15),
            'longitude' => fake()->longitude(25, 33),
            'contact_details' => '+263'.fake()->numerify('#########'),
            'status' => fake()->randomElement(['available', 'sold_out', 'soon_to_be_available']),
            'date_listed' => now(),
        ];
    }
}
