<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Monkey>
 */
class MonkeyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'age' => fake()->numberBetween(1, 30),
            'weight' => fake()->numberBetween(1, 30),
            'elvis_id' => 'EV-' . fake()->randomDigit(),
            'comment' => '-'
        ];
    }
}
