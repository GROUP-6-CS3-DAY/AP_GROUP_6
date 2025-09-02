<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Project;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Outcome>
 */
class OutcomeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_ID' => Project::factory(),
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'artifact_link' => $this->faker->url,
            'outcome_type' => $this->faker->word,
            'quality_certification' => $this->faker->word,
            'commercialization_status' => $this->faker->word,
            'impact' => $this->faker->paragraph,
            'date_achieved' => $this->faker->date,
        ];
    }
}
