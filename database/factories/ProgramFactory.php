<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Program>
 */
class ProgramFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3), // random short title
            'description' => $this->faker->paragraph(), // random text
            'national_alignment' => $this->faker->sentence(),
            'focus_areas' => implode(', ', $this->faker->words(4)),
            'phases' => implode(', ', $this->faker->words(3)),
        ];
    }
}
