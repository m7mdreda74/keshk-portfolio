<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title'        => $this->faker->sentence(3),
            'category'     => $this->faker->randomElement(['web', 'mobile', 'api']),
            'description'  => $this->faker->paragraph(),
            'image'        => null,
            'details_link' => $this->faker->url(),
        ];
    }
}
