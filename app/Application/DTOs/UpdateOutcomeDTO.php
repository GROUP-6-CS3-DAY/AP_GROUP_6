<?php

namespace App\Application\DTOs;

class UpdateOutcomeDTO
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

    public function toArray(): array
    {
        return [
            'project_id' => $this->projectId,
            'title' => $this->title,
            'description' => $this->description,
            'outcome_type' => $this->outcomeType,
            'quality_certification' => $this->qualityCertification,
            'date_achieved' => $this->dateAchieved,
            'commercialization_status' => $this->commercializationStatus,
            'impact' => $this->impact,
            'artifact_link' => $this->artifactLink,
        ];
    }
}
