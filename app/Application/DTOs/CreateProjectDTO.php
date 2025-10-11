<?php

namespace App\Application\DTOs;

class CreateProjectDTO
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
}
