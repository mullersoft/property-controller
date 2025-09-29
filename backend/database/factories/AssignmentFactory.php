<?php

namespace Database\Factories;

use App\Models\Employee;
use App\models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Assignment>
 */
class AssignmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'employee_id' => Employee::inRandomOrder()->first()->id,
            'property_id' => Property::inRandomOrder()
                ->first()
                ->id,
            'assigned_date' => fake()->date(),
            'return_date' => fake()->date(),
        ];
    }
}
