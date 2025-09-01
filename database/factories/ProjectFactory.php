<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'nature_of_project' => $this->faker->word,
            'description' => $this->faker->paragraph(),
            'innovation_focus' => $this->faker->word,
            'prototype_stage' => $this->faker->word,
            'testing_requirements' => $this->faker->word,
            'commercialization_plan' => $this->faker->word,
        ];
    }
}
