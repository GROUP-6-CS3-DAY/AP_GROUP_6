<?php

namespace Database\Factories;

use App\Models\Facility;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Facility>
 */
class FacilityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $facilityTypes = array_keys(Facility::getFacilityTypeOptions());
        $capabilities = array_keys(Facility::getCapabilityOptions());

        return [
            'name' => fake()->company() . ' ' . fake()->randomElement(['Workshop', 'Lab', 'Center', 'Hub']),
            'location' => fake()->city() . ', ' . fake()->state(),
            'description' => fake()->paragraph(),
            'partner_organization' => fake()->randomElement(['UniPod', 'UIRI', 'Lwera Lab', 'SCIT', 'CEDAT']),
            'facility_type' => fake()->randomElement($facilityTypes),
            'capabilities' => fake()->randomElements($capabilities, fake()->numberBetween(2, 5)),
        ];
    }

    /**
     * Indicate that the facility is a workshop.
     */
    public function workshop(): static
    {
        return $this->state(fn(array $attributes) => [
            'facility_type' => 'workshop',
        ]);
    }

    /**
     * Indicate that the facility is a testing center.
     */
    public function testingCenter(): static
    {
        return $this->state(fn(array $attributes) => [
            'facility_type' => 'testing_center',
        ]);
    }

    /**
     * Indicate that the facility is a laboratory.
     */
    public function laboratory(): static
    {
        return $this->state(fn(array $attributes) => [
            'facility_type' => 'laboratory',
        ]);
    }

    /**
     * Indicate that the facility is a maker space.
     */
    public function makerSpace(): static
    {
        return $this->state(fn(array $attributes) => [
            'facility_type' => 'maker_space',
        ]);
    }
}
