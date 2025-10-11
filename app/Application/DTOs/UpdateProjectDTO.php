<?php

namespace App\Application\DTOs;

class UpdateProjectDTO
{
    public function __construct(
        public readonly string $programId,
        public readonly string $facilityId,
        public readonly string $title,
        public readonly string $natureOfProject,
        public readonly string $description,
        public readonly string $innovationFocus,
        public readonly string $prototypeStage,
        public readonly string $testingRequirements,
        public readonly string $commercializationPlan
    ) {}

    public function toArray(): array
    {
        return [
            'program_id' => $this->programId,
            'facility_id' => $this->facilityId,
            'title' => $this->title,
            'nature_of_project' => $this->natureOfProject,
            'description' => $this->description,
            'innovation_focus' => $this->innovationFocus,
            'prototype_stage' => $this->prototypeStage,
            'testing_requirements' => $this->testingRequirements,
            'commercialization_plan' => $this->commercializationPlan,
        ];
    }
}
