<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\OutcomeType;
use App\Domain\ValueObjects\CommercializationStatus;
use Carbon\Carbon;

class Outcome
{
    private string $id;
    private string $title;
    private string $description;
    private string $projectId;
    private OutcomeType $outcomeType;
    private string $qualityCertification;
    private Carbon $dateAchieved;
    private CommercializationStatus $commercializationStatus;
    private string $impact;
    private string $artifactLink;

    public function __construct(
        string $id,
        string $projectId,
        string $title,
        string $description,
        OutcomeType $outcomeType,
        string $qualityCertification,
        string $dateAchieved,
        CommercializationStatus $commercializationStatus,
        string $impact = '',
        string $artifactLink = ''
    ) {
        $this->validateRequiredFields($projectId, $title, $description);
        $this->validateBusinessRules(['title' => $title, 'description' => $description, 'date_achieved' => $dateAchieved]);
        
        $this->id = $id;
        $this->projectId = $projectId;
        $this->title = $title;
        $this->description = $description;
        $this->outcomeType = $outcomeType;
        $this->qualityCertification = $qualityCertification;
        $this->dateAchieved = Carbon::parse($dateAchieved);
        $this->commercializationStatus = $commercializationStatus;
        $this->impact = $impact;
        $this->artifactLink = $artifactLink;
    }

    // Getters
    public function getId(): string { return $this->id; }
    public function getTitle(): string { return $this->title; }
    public function getDescription(): string { return $this->description; }
    public function getProjectId(): string { return $this->projectId; }
    public function getOutcomeType(): OutcomeType { return $this->outcomeType; }
    public function getQualityCertification(): string { return $this->qualityCertification; }
    public function getDateAchieved(): Carbon
    {
        // Ensure we always return a Carbon instance
        if ($this->dateAchieved instanceof Carbon) {
            return $this->dateAchieved;
        }
        
        // If it's a string, parse it to Carbon
        return Carbon::parse($this->dateAchieved);
    }
    public function getCommercializationStatus(): CommercializationStatus { return $this->commercializationStatus; }
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
        $this->qualityCertification = $data['quality_certification'] ?? $this->qualityCertification;
        $this->dateAchieved = isset($data['date_achieved']) ? Carbon::parse($data['date_achieved']) : $this->dateAchieved;
        $this->impact = $data['impact'] ?? $this->impact;
        $this->artifactLink = $data['artifact_link'] ?? $this->artifactLink;
    }

    private function validateRequiredFields(string $projectId, string $title, string $description): void
    {
        if (empty($projectId)) {
            throw new \DomainException('Outcome.ProjectId is required');
        }
        
        if (empty($title)) {
            throw new \DomainException('Outcome.Title is required');
        }
        
        if (empty($description)) {
            throw new \DomainException('Outcome.Description is required');
        }
    }

    private function validateBusinessRules(array $data): void
    {
        // Validate title length
        if (isset($data['title']) && strlen($data['title']) < 5) {
            throw new \DomainException('Outcome title must be at least 5 characters long');
        }
        
        // Validate description length
        if (isset($data['description']) && strlen($data['description']) < 15) {
            throw new \DomainException('Outcome description must be at least 15 characters long');
        }

        // Business rule: Date achieved cannot be in the future
        if (isset($data['date_achieved']) && Carbon::parse($data['date_achieved'])->isFuture()) {
            throw new \DomainException('Outcome date achieved cannot be in the future');
        }

        // Business rule: Artifact link must be accessible
        if (isset($data['artifact_link']) && !empty($data['artifact_link']) && !filter_var($data['artifact_link'], FILTER_VALIDATE_URL)) {
            throw new \DomainException('Invalid artifact link format');
        }
    }

    // Domain behavior methods
    public function canBeCommercializaed(): bool
    {
        return $this->commercializationStatus->isCommercializable();
    }

    public function isReadyForCommercialization(): bool
    {
        return $this->commercializationStatus->isCommercializable();
    }

    public function hasQualityCertification(): bool
    {
        return !empty($this->qualityCertification);
    }

    public function hasSignificantImpact(): bool
    {
        return !empty($this->impact);
    }

    public function hasArtifactLink(): bool
    {
        return !empty($this->artifactLink);
    }

    public function requiresIntellectualPropertyProtection(): bool
    {
        return $this->outcomeType->getValue() === 'patent';
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
