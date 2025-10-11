<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\ProgramPhase;

class Program
{
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
        $this->validateBusinessRules($data);
        
        $this->name = $data['name'] ?? $this->name;
        $this->description = $data['description'] ?? $this->description;
        $this->nationalAlignment = $data['national_alignment'] ?? $this->nationalAlignment;
        $this->focusAreas = $data['focus_areas'] ?? $this->focusAreas;
        
        if (isset($data['phases'])) {
            $this->phases = array_map(fn($phase) => $phase instanceof ProgramPhase ? $phase : new ProgramPhase($phase), $data['phases']);
        }
    }

    private function validateBusinessRules(array $data): void
    {
        if (isset($data['name']) && strlen($data['name']) < 3) {
            throw new \DomainException('Program name must be at least 3 characters long');
        }

        if (isset($data['description']) && strlen($data['description']) < 10) {
            throw new \DomainException('Program description must be at least 10 characters long');
        }

        if (isset($data['focus_areas']) && empty($data['focus_areas'])) {
            throw new \DomainException('Program must have at least one focus area');
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
