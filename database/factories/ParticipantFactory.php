<?php

namespace Database\Factories;

use App\Models\Participant;
use Illuminate\Database\Eloquent\Factories\Factory;

class ParticipantFactory extends Factory
{
    protected $model = Participant::class;

    public function definition()
    {
        $crossSkillTrained = $this->faker->boolean();
        
        return [
            'full_name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'affiliation' => $this->faker->company(),
            'cross_skill_trained' => $crossSkillTrained,
            'specialization' => $crossSkillTrained ? $this->faker->word() : null
        ];
    }
}
