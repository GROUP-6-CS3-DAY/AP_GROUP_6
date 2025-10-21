<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\InnovationFocus;
use App\Domain\ValueObjects\PrototypeStage;
use App\Domain\ValueObjects\ProjectStatus;
use App\Models\Project as ProjectModel;


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
    private ProjectStatus $status;
    private array $participants;
    private array $outcomes;
    private array $technicalRequirements;

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
        ProjectStatus $status = null,
        array $participants = [],
        array $outcomes = [],
        array $technicalRequirements = []
    ) {
        logger()->info("Project Entity: Creating project", [
            'id' => $id,
            'title' => $title,
            'participants_count' => count($participants),
            'outcomes_count' => count($outcomes),
            'participants_types' => array_map(fn($p) => get_class($p), $participants),
            'outcomes_types' => array_map(fn($o) => get_class($o), $outcomes)
        ]);

        $this->validateRequiredAssociations($programId, $facilityId);
        
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
        $this->status = $status ?? new ProjectStatus('planning');
        $this->participants = $participants; // Now expects array of Participant entities
        $this->outcomes = $outcomes; // Now expects array of Outcome entities
        $this->technicalRequirements = $technicalRequirements;

        logger()->info("Project Entity: Project created successfully", [
            'id' => $this->id,
            'final_participants_count' => count($this->participants),
            'final_outcomes_count' => count($this->outcomes)
        ]);
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
    public function getStatus(): ProjectStatus { return $this->status; }
    public function hasParticipants(): bool { return !empty($this->participants); }
    public function hasOutcomes(): bool { return !empty($this->outcomes); }
    public function getOutcomes(): array { 
        logger()->debug("Project Entity: getOutcomes() called", [
            'project_id' => $this->id,
            'outcomes_count' => count($this->outcomes)
        ]);
        
        return $this->outcomes; 
    }
    public function getTechnicalRequirements(): array { return $this->technicalRequirements; }

    // Simplified getParticipants method
    public function getParticipants(): array
    {
        logger()->debug("Project Entity: getParticipants() called", [
            'project_id' => $this->id,
            'participants_count' => count($this->participants)
        ]);
        
        return $this->participants;
    }

    // Business logic methods
    public function update(array $data): void
    {
        $programId = $data['program_id'] ?? $this->programId;
        $facilityId = $data['facility_id'] ?? $this->facilityId;
        
        $this->validateRequiredAssociations($programId, $facilityId);
        $this->validateBusinessRules($data);
        
        $this->programId = $programId;
        $this->facilityId = $facilityId;
        $this->title = $data['title'] ?? $this->title;
        $this->natureOfProject = $data['nature_of_project'] ?? $this->natureOfProject;
        $this->description = $data['description'] ?? $this->description;
        $this->innovationFocus = isset($data['innovation_focus']) ? new InnovationFocus($data['innovation_focus']) : $this->innovationFocus;
        $this->prototypeStage = isset($data['prototype_stage']) ? new PrototypeStage($data['prototype_stage']) : $this->prototypeStage;
        $this->testingRequirements = $data['testing_requirements'] ?? $this->testingRequirements;
        $this->commercializationPlan = $data['commercialization_plan'] ?? $this->commercializationPlan;
        
        if (isset($data['status'])) {
            $newStatus = new ProjectStatus($data['status']);
            $this->validateStatusChange($newStatus);
            $this->status = $newStatus;
        }
        
        if (isset($data['technical_requirements'])) {
            $this->technicalRequirements = $data['technical_requirements'];
        }
    }

    private function validateRequiredAssociations(string $programId, string $facilityId): void
    {
        if (empty($programId) || empty($facilityId)) {
            throw new \DomainException('Project.ProgramId and Project.FacilityId are required');
        }
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

    private function validateStatusChange(ProjectStatus $newStatus): void
    {
        // Business rule: Completed projects must have at least one outcome
        if ($newStatus->getValue() === 'completed' && empty($this->outcomes)) {
            throw new \DomainException('Completed projects must have at least one documented outcome');
        }
    }

    public function validateTeamAssignment(): void
    {
        // Business rule: Project must have at least one team member
        if (empty($this->participants)) {
            throw new \DomainException('Project must have at least one team member assigned');
        }
    }

    public function validateFacilityCompatibility(array $facilityCapabilities): void
    {
        // Business rule: Project requirements must be compatible with facility capabilities
        foreach ($this->technicalRequirements as $requirement) {
            if (!in_array($requirement, $facilityCapabilities)) {
                throw new \DomainException('Project requirements not compatible with facility capabilities');
            }
        }
    }

    public function validateNameUniquenessInProgram(array $existingProjectNamesInProgram): void
    {
        // Business rule: Project name must be unique within program
        $normalizedTitle = strtolower(trim($this->title));
        $normalizedExistingNames = array_map(fn($name) => strtolower(trim($name)), $existingProjectNamesInProgram);
        
        if (in_array($normalizedTitle, $normalizedExistingNames)) {
            throw new \DomainException('A project with this name already exists in this program');
        }
    }

    public function addParticipant(string $participantId): void
    {
        // This method now needs to work with participant IDs for business logic
        $participantIds = array_map(fn($p) => $p->getId(), $this->participants);
        if (!in_array($participantId, $participantIds)) {
            // Note: In a real implementation, you'd need to load the participant entity
            // This is a simplified version for business logic validation
        }
    }

    public function removeParticipant(string $participantId): void
    {
        $this->participants = array_filter(
            $this->participants, 
            fn($participant) => $participant->getId() !== $participantId
        );
        
        $this->validateTeamAssignment();
    }

    public function addOutcome(string $outcomeId): void
    {
        $outcomeIds = array_map(fn($o) => $o->getId(), $this->outcomes);
        if (!in_array($outcomeId, $outcomeIds)) {
            // Note: In a real implementation, you'd need to load the outcome entity
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

    public function isCompleted(): bool
    {
        return $this->status->getValue() === 'completed';
    }

    public function canBeCompleted(): bool
    {
        return !empty($this->outcomes);
    }
}
