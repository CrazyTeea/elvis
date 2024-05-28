<?php

namespace Database\Factories;

use App\Models\Experiment;
use App\Models\Figure;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class FigureFactory extends Factory
{
    protected $model = Figure::class;

    public function definition(): array
    {
        return [
            'x' => $this->faker->randomFloat(),
            'y' => $this->faker->randomFloat(),
            'w' => $this->faker->randomFloat(),
            'h' => $this->faker->randomFloat(),
            'color' => $this->faker->word(),
            'brightness' => $this->faker->randomFloat(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'name' => $this->faker->name(),
            'reaction_time' => $this->faker->randomFloat(),
            'size_min' => $this->faker->randomNumber(),
            'size_max' => $this->faker->randomNumber(),
            'brightness_min' => $this->faker->randomNumber(),
            'brightness_max' => $this->faker->randomNumber(),
            'angle' => $this->faker->randomNumber(),
            'angles' => $this->faker->randomNumber(),
            'colors' => $this->faker->word(),
            'result_id' => $this->faker->randomNumber(),

            'experiment_id' => Experiment::factory(),
        ];
    }
}
