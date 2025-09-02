<?php

namespace Database\Factories;

use App\Models\Equipment;
use App\Models\Facility;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Equipment>
 */
class EquipmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $usageDomains = array_keys(Equipment::getUsageDomainOptions());
        $supportPhases = array_keys(Equipment::getSupportPhaseOptions());

        return [
            'facility_id' => Facility::factory(),
            'name' => fake()->randomElement([
                'CNC Machine',
                '3D Printer',
                'PCB Etching Machine',
                'Oscilloscope',
                'Multimeter',
                'Soldering Station',
                'Drill Press',
                'Laser Cutter',
                'Microcontroller Kit',
                'Sensor Kit',
                'Power Supply',
                'Function Generator'
            ]),
            'capabilities' => fake()->randomElements([
                'Precision cutting',
                '3D modeling',
                'Circuit testing',
                'Signal analysis',
                'Voltage measurement',
                'Component assembly',
                'Material drilling',
                'Laser engraving',
                'Programming',
                'Data collection',
                'Power regulation',
                'Waveform generation'
            ], fake()->numberBetween(2, 4)),
            'description' => fake()->paragraph(),
            'inventory_code' => 'EQ-' . fake()->unique()->numberBetween(1000, 9999),
            'usage_domain' => fake()->randomElement($usageDomains),
            'support_phase' => fake()->randomElement($supportPhases),
        ];
    }

    /**
     * Indicate that the equipment is for electronics.
     */
    public function electronics(): static
    {
        return $this->state(fn(array $attributes) => [
            'usage_domain' => 'electronics',
        ]);
    }

    /**
     * Indicate that the equipment is for mechanical work.
     */
    public function mechanical(): static
    {
        return $this->state(fn(array $attributes) => [
            'usage_domain' => 'mechanical',
        ]);
    }

    /**
     * Indicate that the equipment is for IoT.
     */
    public function iot(): static
    {
        return $this->state(fn(array $attributes) => [
            'usage_domain' => 'iot',
        ]);
    }

    /**
     * Indicate that the equipment is for training.
     */
    public function training(): static
    {
        return $this->state(fn(array $attributes) => [
            'support_phase' => 'training',
        ]);
    }

    /**
     * Indicate that the equipment is for prototyping.
     */
    public function prototyping(): static
    {
        return $this->state(fn(array $attributes) => [
            'support_phase' => 'prototyping',
        ]);
    }
}
