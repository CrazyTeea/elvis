<?php

namespace Database\Factories;

use App\Models\Helpers;
use Illuminate\Database\Eloquent\Factories\Factory;

class HelpersFactory extends Factory
{
    protected $model = Helpers::class;

    public function definition(): array
    {
        return [
            'experiment_id' => rand(408,410),
            'name' => $this->faker->shuffleString('left'),
        ];
    }
}
