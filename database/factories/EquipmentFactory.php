<?php

namespace Database\Factories;

use App\Models\Facility;
use App\Models\Equipment;
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
        $capabilityKeys = array_keys(Equipment::getCapabilityOptions());
        $usageKeys = array_keys(Equipment::getUsageDomainOptions());
        $phaseKeys = array_keys(Equipment::getSupportPhaseOptions());

        return [
            'facility_id' => Facility::factory(),
            'name' => $this->faker->word(),
            'capabilities' => $this->faker->randomElements($capabilityKeys, $this->faker->numberBetween(2, 5)),
            'description' => $this->faker->paragraph(),
            'inventory_code' => $this->faker->unique()->bothify('EQP-####-???'),
            'usage_domain' => $this->faker->randomElement($usageKeys),
            'support_phase' => $this->faker->randomElement($phaseKeys),
        ];
    }
}
