<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FileFactory extends Factory
{

    /**
     * @inheritDoc
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->filePath(),
            'experiment_id' => $this->faker->numberBetween(1, 60),
            'monkey_id' => $this->faker->numberBetween(1, 7),
        ];
    }
}
