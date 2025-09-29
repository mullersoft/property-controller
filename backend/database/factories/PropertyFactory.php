<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Property>
 */
class PropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->word(),
            'category_id' => Category::inRandomOrder()->first()->id,
            'purchase_date' => fake()->date(),
            'purchase_cost' => fake()->numberBetween(100, 1000),
            'status' => fake()->randomElement(['available', 'assigned', 'maintenance']),
            'serial_number' => fake()->unique()->numberBetween(1000, 9000),
            'model_number' => fake()->numberBetween(1000, 9000),
            'manufacturer' => fake()->company(),
            'current_value' => fake()->numberBetween(100, 900),
        ];
    }
};
