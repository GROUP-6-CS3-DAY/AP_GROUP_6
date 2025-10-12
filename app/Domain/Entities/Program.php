<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\ProgramPhase;

class Program
{
    private const VALID_NATIONAL_ALIGNMENTS = ['NDPIII', 'DigitalRoadmap2023_2028', '4IR'];

    private string $id;
    private string $name;
    private string $description;
    private string $nationalAlignment;
    private array $focusAreas;
    private array $phases;
    private array $projects;

    public function __construct(
        string $id,
        string $name,
        string $description,
        string $nationalAlignment,
        array $focusAreas,
        array $phases,
        array $projects = []
    ) {
        $this->validateRequiredFields($name, $description);
        $this->validateConstructorRules($name, $description, $nationalAlignment, $focusAreas);
        
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->nationalAlignment = $nationalAlignment;
        $this->focusAreas = $focusAreas;
        $this->phases = array_map(fn($phase) => $phase instanceof ProgramPhase ? $phase : new ProgramPhase($phase), $phases);
        $this->projects = $projects;
    }

    // Getters
    public function getId(): string { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getDescription(): string { return $this->description; }
    public function getNationalAlignment(): string { return $this->nationalAlignment; }
    public function getFocusAreas(): array { return $this->focusAreas; }
    public function getPhases(): array { return $this->phases; }
    public function getProjects(): array { return $this->projects; }

    // Business logic methods
    public function update(array $data): void
    {
        $name = $data['name'] ?? $this->name;
        $description = $data['description'] ?? $this->description;
        $nationalAlignment = $data['national_alignment'] ?? $this->nationalAlignment;
        $focusAreas = $data['focus_areas'] ?? $this->focusAreas;
        
        $this->validateUpdateRules($name, $description, $nationalAlignment, $focusAreas);
        
        $this->name = $name;
        $this->description = $description;
        $this->nationalAlignment = $nationalAlignment;
        $this->focusAreas = $focusAreas;
        
        if (isset($data['phases'])) {
            $this->phases = array_map(fn($phase) => $phase instanceof ProgramPhase ? $phase : new ProgramPhase($phase), $data['phases']);
        }
    }

    private function validateRequiredFields(string $name, string $description): void
    {
        if (empty(trim($name))) {
            throw new \DomainException('Program.Name is required');
        }

        if (empty(trim($description))) {
            throw new \DomainException('Program.Description is required');
        }
    }

    private function validateConstructorRules(string $name, string $description, string $nationalAlignment, array $focusAreas): void
    {
        if (strlen($name) < 3) {
            throw new \DomainException('Program name must be at least 3 characters long', strlen($name));
        }

        if (strlen($description) < 10) {
            throw new \DomainException('Program description must be at least 10 characters long, but was ' . strlen($description) .  $description);
        }

        // National Alignment rule: required when focus areas are specified
        if (!empty($focusAreas)) {
            if (empty(trim($nationalAlignment))) {
                throw new \DomainException('Program.NationalAlignment must include at least one recognized alignment when FocusAreas are specified');
            }
            
            if (!$this->hasValidNationalAlignment($nationalAlignment)) {
                throw new \DomainException('Program.NationalAlignment must include at least one recognized alignment when FocusAreas are specified');
            }
        }

        // Allow empty focus areas during construction (for programs that might be set up later)
    }

    private function validateUpdateRules(string $name, string $description, string $nationalAlignment, array $focusAreas): void
    {
        if (strlen($name) < 3) {
            throw new \DomainException('Program name must be at least 3 characters long');
        }

        if (strlen($description) < 10) {
            throw new \DomainException('Program description must be at least 10 characters long');
        }

        // National Alignment rule: required when focus areas are specified
        if (!empty($focusAreas)) {
            if (empty(trim($nationalAlignment))) {
                throw new \DomainException('Program.NationalAlignment must include at least one recognized alignment when FocusAreas are specified');
            }
            
            if (!$this->hasValidNationalAlignment($nationalAlignment)) {
                throw new \DomainException('Program.NationalAlignment must include at least one recognized alignment when FocusAreas are specified');
            }
        }

        // During update, explicitly setting empty focus areas should fail
        if (isset($focusAreas) && empty($focusAreas)) {
            throw new \DomainException('Program must have at least one focus area');
        }
    }

    private function hasValidNationalAlignment(string $nationalAlignment): bool
    {
        $alignmentTokens = array_map('trim', explode(',', $nationalAlignment));
        
        foreach ($alignmentTokens as $token) {
            if (in_array($token, self::VALID_NATIONAL_ALIGNMENTS)) {
                return true;
            }
        }
        
        return false;
    }

    public function canBeDeleted(): bool
    {
        return empty($this->projects);
    }

    public function validateDeletion(): void
    {
        if (!$this->canBeDeleted()) {
            throw new \DomainException('Program has Projects; archive or reassign before delete');
        }
    }

    public function getProjectCount(): int
    {
        return count($this->projects);
    }

    public function getFocusAreasAsString(): string
    {
        return implode(', ', $this->focusAreas);
    }

    public function getPhasesAsString(): string
    {
        return implode(', ', array_map(fn($phase) => $phase->getDisplayName(), $this->phases));
    }

    public function isActive(): bool
    {
        return !empty($this->phases) && !empty($this->focusAreas);
    }

    public function canAcceptProjects(): bool
    {
        return $this->isActive() && !empty($this->nationalAlignment);
    }
}
