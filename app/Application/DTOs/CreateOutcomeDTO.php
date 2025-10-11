<?php

namespace App\Application\DTOs;

class CreateOutcomeDTO
{
    public function __construct(
        public readonly string $projectId,
        public readonly string $title,
        public readonly string $description,
        public readonly string $outcomeType,
        public readonly string $qualityCertification,
        public readonly string $dateAchieved,
        public readonly string $commercializationStatus,
        public readonly string $impact,
        public readonly string $artifactLink
    ) {}
}
