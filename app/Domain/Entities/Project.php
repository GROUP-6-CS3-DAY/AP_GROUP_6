<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\InnovationFocus;
use App\Domain\ValueObjects\PrototypeStage;

class Project
{
    private string $id;
    private string $programId;
    private string $facilityId;
    private string $title;
    private string $natureOfProject;
    private string $description;
    private InnovationFocus $innovationFocus;
    private PrototypeStage $prototypeStage;
    private string $testingRequirements;
    private string $commercializationPlan;
    private array $participants;
    private array $outcomes;

    public function __construct(
        string $id,
        string $programId,
        string $facilityId,
        string $title,
        string $natureOfProject,
        string $description,
        InnovationFocus $innovationFocus,
        PrototypeStage $prototypeStage,
        string $testingRequirements,
        string $commercializationPlan,
        array $participants = [],
        array $outcomes = []
    ) {
        $this->id = $id;
        $this->programId = $programId;
        $this->facilityId = $facilityId;
        $this->title = $title;
        $this->natureOfProject = $natureOfProject;
        $this->description = $description;
        $this->innovationFocus = $innovationFocus;
        $this->prototypeStage = $prototypeStage;
        $this->testingRequirements = $testingRequirements;
        $this->commercializationPlan = $commercializationPlan;
        $this->participants = $participants;
        $this->outcomes = $outcomes;
    }

    // Getters
    public function getId(): string { return $this->id; }
    public function getProgramId(): string { return $this->programId; }
    public function getFacilityId(): string { return $this->facilityId; }
    public function getTitle(): string { return $this->title; }
    public function getNatureOfProject(): string { return $this->natureOfProject; }
    public function getDescription(): string { return $this->description; }
    public function getInnovationFocus(): InnovationFocus { return $this->innovationFocus; }
    public function getPrototypeStage(): PrototypeStage { return $this->prototypeStage; }
    public function getTestingRequirements(): string { return $this->testingRequirements; }
    public function getCommercializationPlan(): string { return $this->commercializationPlan; }
    public function getParticipants(): array { return $this->participants; }
    public function getOutcomes(): array { return $this->outcomes; }

    // Business logic methods
    public function update(array $data): void
    {
        $this->validateBusinessRules($data);
        
        $this->programId = $data['program_id'] ?? $this->programId;
        $this->facilityId = $data['facility_id'] ?? $this->facilityId;
        $this->title = $data['title'] ?? $this->title;
        $this->natureOfProject = $data['nature_of_project'] ?? $this->natureOfProject;
        $this->description = $data['description'] ?? $this->description;
        $this->innovationFocus = isset($data['innovation_focus']) ? new InnovationFocus($data['innovation_focus']) : $this->innovationFocus;
        $this->prototypeStage = isset($data['prototype_stage']) ? new PrototypeStage($data['prototype_stage']) : $this->prototypeStage;
        $this->testingRequirements = $data['testing_requirements'] ?? $this->testingRequirements;
        $this->commercializationPlan = $data['commercialization_plan'] ?? $this->commercializationPlan;
    }

    private function validateBusinessRules(array $data): void
    {
        if (isset($data['title']) && strlen($data['title']) < 5) {
            throw new \DomainException('Project title must be at least 5 characters long');
        }

        if (isset($data['description']) && strlen($data['description']) < 20) {
            throw new \DomainException('Project description must be at least 20 characters long');
        }
    }

    public function canAdvanceToNextStage(): bool
    {
        return $this->prototypeStage->canAdvance();
    }

    public function isReadyForCommercialization(): bool
    {
        return $this->prototypeStage->getValue() === 'production' && 
               !empty($this->commercializationPlan);
    }

    public function getParticipantCount(): int
    {
        return count($this->participants);
    }

    public function getOutcomeCount(): int
    {
        return count($this->outcomes);
    }
}
