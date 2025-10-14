<?php

namespace App\Application\DTOs;

class UpdateOutcomeDTO
{
    public function __construct(
        public readonly string $projectId,
        public readonly string $title,
        public readonly string $description,
        public readonly string $outcomeType,
        public readonly string $dateAchieved,
        public readonly ?string $commercializationStatus = null,
        public readonly ?string $qualityCertification = null,
        public readonly ?string $impact = null,
        public readonly ?string $artifactLink = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            projectId: $data['project_id'],
            title: $data['title'],
            description: $data['description'],
            outcomeType: $data['outcome_type'],
            dateAchieved: $data['date_achieved'],
            commercializationStatus: $data['commercialization_status'] ?? null,
            qualityCertification: $data['quality_certification'] ?? null,
            impact: $data['impact'] ?? null,
            artifactLink: $data['artifact_link'] ?? null
        );
    }
}
