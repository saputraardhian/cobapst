<?php

namespace Database\Factories;

use App\Models\PstVisitor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PstVisitor>
 */
class PstVisitorFactory extends Factory
{
    protected $model = PstVisitor::class;

    public function definition(): array
    {
        return [
            'phone_number' => fake()->unique()->numerify('08##########'),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'institution' => fake()->company(),
            'address' => fake()->address(),
        ];
    }
}
