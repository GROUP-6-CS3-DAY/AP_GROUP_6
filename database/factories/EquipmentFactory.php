<?php

namespace Database\Factories;
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
        return [
            'facility_ID' => Facility::factory(),
            'name' => $this->faker->word(),
            'capabilities' => $this->faker->text(),
            'description' => $this->faker->text(),
            'inventory_code' => $this->faker->word(),
            'usage_domain' => $this->faker->word(),
            'support_phase' => $this->faker->word(),
        ];
    }
}
