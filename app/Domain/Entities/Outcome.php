<?php

namespace App\Domain\Entities;

use Carbon\Carbon;

class Outcome
{
    private string $id;
    private string $title;
    private string $description;
    private string $projectId;
    private string $outcomeType;
    private string $qualityCertification;
    private Carbon $dateAchieved;
    private string $commercializationStatus;
    private string $impact;
    private string $artifactLink;

    public function __construct(
        string $id,
        string $title,
        string $description,
        string $projectId,
        string $outcomeType,
        string $qualityCertification,
        Carbon $dateAchieved,
        string $commercializationStatus,
        string $impact,
        string $artifactLink
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->projectId = $projectId;
        $this->outcomeType = $outcomeType;
        $this->qualityCertification = $qualityCertification;
        $this->dateAchieved = $dateAchieved;
        $this->commercializationStatus = $commercializationStatus;
        $this->impact = $impact;
        $this->artifactLink = $artifactLink;
    }

    // Getters
    public function getId(): string { return $this->id; }
    public function getTitle(): string { return $this->title; }
    public function getDescription(): string { return $this->description; }
    public function getProjectId(): string { return $this->projectId; }
    public function getOutcomeType(): string { return $this->outcomeType; }
    public function getQualityCertification(): string { return $this->qualityCertification; }
    public function getDateAchieved(): Carbon { return $this->dateAchieved; }
    public function getCommercializationStatus(): string { return $this->commercializationStatus; }
    public function getImpact(): string { return $this->impact; }
    public function getArtifactLink(): string { return $this->artifactLink; }

    // Business logic methods
    public function update(array $data): void
    {
        // Domain validation rules
        $this->validateBusinessRules($data);
        
        $this->title = $data['title'] ?? $this->title;
        $this->description = $data['description'] ?? $this->description;
        $this->projectId = $data['project_id'] ?? $this->projectId;
        $this->outcomeType = $data['outcome_type'] ?? $this->outcomeType;
        $this->qualityCertification = $data['quality_certification'] ?? $this->qualityCertification;
        $this->dateAchieved = isset($data['date_achieved']) ? Carbon::parse($data['date_achieved']) : $this->dateAchieved;
        $this->commercializationStatus = $data['commercialization_status'] ?? $this->commercializationStatus;
        $this->impact = $data['impact'] ?? $this->impact;
        $this->artifactLink = $data['artifact_link'] ?? $this->artifactLink;
    }

    private function validateBusinessRules(array $data): void
    {
        // Business rule: Date achieved cannot be in the future
        if (isset($data['date_achieved']) && Carbon::parse($data['date_achieved'])->isFuture()) {
            throw new \DomainException('Date achieved cannot be in the future');
        }

        // Business rule: Artifact link must be accessible
        if (isset($data['artifact_link']) && !filter_var($data['artifact_link'], FILTER_VALIDATE_URL)) {
            throw new \DomainException('Invalid artifact link format');
        }
    }

    // Domain behavior methods
    public function canBeCommercializaed(): bool
    {
        return in_array($this->commercializationStatus, ['Ready', 'In Progress', 'Commercialized']);
    }

    public function isHighImpact(): bool
    {
        return in_array(strtolower($this->impact), ['high', 'critical', 'transformative']);
    }

    public function getDaysToAchievement(): int
    {
        return Carbon::now()->diffInDays($this->dateAchieved, false);
    }
}
