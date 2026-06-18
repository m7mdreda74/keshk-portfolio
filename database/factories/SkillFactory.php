<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SkillFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'       => $this->faker->word(),
            'percentage' => $this->faker->numberBetween(10, 100),
        ];
    }
}
