<?php

namespace Database\Factories;

use App\Models\Experiment;
use App\Models\Figure;
use App\Models\FigureResult;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class FigureResultFactory extends Factory
{
    protected $model = FigureResult::class;

    public function definition(): array
    {
        return [
            'reaction_time' => $this->faker->randomFloat(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'figure_id' => Figure::factory(),
            'experiment_id' => Experiment::factory(),
        ];
    }
}
