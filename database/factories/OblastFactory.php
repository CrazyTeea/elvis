<?php

namespace Database\Factories;

use App\Models\Oblast;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class OblastFactory extends Factory
{
    protected $model = Oblast::class;

    public function definition(): array
    {
        return [
            'experiment_id' => $this->faker->randomNumber(),
            'br_min' => $this->faker->randomNumber(),
            'br_max' => $this->faker->randomNumber(),
            'x1' => $this->faker->randomNumber(),
            'x2' => $this->faker->randomNumber(),
            'y1' => $this->faker->randomNumber(),
            'y2' => $this->faker->randomNumber(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
