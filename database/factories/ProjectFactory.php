<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Program;
use App\Models\Facility;

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
            'program_ID' => Program::factory(),
            'facility_ID' => Facility::factory(),
            'title' => $this->faker->sentence,
            'nature_of_project' => $this->faker->word,
            'description' => $this->faker->paragraph,
            'innovation_focus' => $this->faker->word,
            'prototype_stage' => $this->faker->word,
            'testing_requirements' => $this->faker->word,
            'commercialization_plan' => $this->faker->word,
        ];
    }
}
