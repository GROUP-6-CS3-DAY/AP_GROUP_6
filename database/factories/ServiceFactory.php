<?php

namespace Database\Factories;

use App\Models\Facility;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = array_keys(Service::getCategoryOptions());
        $skillTypes = array_keys(Service::getSkillTypeOptions());

        return [
            'facility_id' => Facility::factory(),
            'name' => fake()->randomElement([
                'CNC Machining',
                'PCB Fabrication',
                '3D Printing',
                'Materials Testing',
                'Software Development',
                'IoT Prototyping',
                'Welding Services',
                'Electronics Testing',
                'Training Programs',
                'Consultation Services'
            ]),
            'description' => fake()->paragraph(),
            'category' => fake()->randomElement($categories),
            'skill_type' => fake()->randomElement($skillTypes),
        ];
    }

    /**
     * Indicate that the service is for machining.
     */
    public function machining(): static
    {
        return $this->state(fn(array $attributes) => [
            'category' => 'machining',
            'skill_type' => 'hardware',
        ]);
    }

    /**
     * Indicate that the service is for testing.
     */
    public function testing(): static
    {
        return $this->state(fn(array $attributes) => [
            'category' => 'testing',
            'skill_type' => 'hardware',
        ]);
    }

    /**
     * Indicate that the service is for training.
     */
    public function training(): static
    {
        return $this->state(fn(array $attributes) => [
            'category' => 'training',
            'skill_type' => 'integration',
        ]);
    }

    /**
     * Indicate that the service is for prototyping.
     */
    public function prototyping(): static
    {
        return $this->state(fn(array $attributes) => [
            'category' => 'prototyping',
            'skill_type' => 'hardware',
        ]);
    }
}