<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\ServiceCategory;
use App\Domain\ValueObjects\SkillType;

class Service
{
    private string $id;
    private string $facilityId;
    private string $name;
    private string $description;
    private ServiceCategory $category;
    private SkillType $skillType;
    private array $requirements;
    private string $availabilityStatus;
    private float $cost;

    public function __construct(
        string $id,
        string $facilityId,
        string $name,
        string $description,
        ServiceCategory $category,
        SkillType $skillType,
        array $requirements = [],
        string $availabilityStatus = 'available',
        float $cost = 0.0
    ) {
        $this->validateRequiredFields($facilityId, $name);
        
        $this->id = $id;
        $this->facilityId = $facilityId;
        $this->name = $name;
        $this->description = $description;
        $this->category = $category;
        $this->skillType = $skillType;
        $this->requirements = $requirements;
        $this->availabilityStatus = $availabilityStatus;
        $this->cost = $cost;
    }

    // Getters
    public function getId(): string { return $this->id; }
    public function getFacilityId(): string { return $this->facilityId; }
    public function getName(): string { return $this->name; }
    public function getDescription(): string { return $this->description; }
    public function getCategory(): ServiceCategory { return $this->category; }
    public function getSkillType(): SkillType { return $this->skillType; }
    public function getRequirements(): array { return $this->requirements; }
    public function getAvailabilityStatus(): string { return $this->availabilityStatus; }
    public function getCost(): float { return $this->cost; }

    public function update(array $data): void
    {
        $this->name = $data['name'] ?? $this->name;
        $this->description = $data['description'] ?? $this->description;
        $this->requirements = $data['requirements'] ?? $this->requirements;
        $this->availabilityStatus = $data['availability_status'] ?? $this->availabilityStatus;
        $this->cost = $data['cost'] ?? $this->cost;
    }

    private function validateRequiredFields(string $facilityId, string $name): void
    {
        if (empty($facilityId)) {
            throw new \DomainException('Service.FacilityId is required');
        }
        
        if (empty($name)) {
            throw new \DomainException('Service.Name is required');
        }
    }

    // Domain behavior methods
    public function isAvailable(): bool
    {
        return $this->availabilityStatus === 'available';
    }

    public function isInCategory(string $categoryValue): bool
    {
        return $this->category->getValue() === $categoryValue;
    }

    public function requiresSkill(string $skillTypeValue): bool
    {
        return $this->skillType->getValue() === $skillTypeValue;
    }

    public function hasRequirement(string $requirement): bool
    {
        return in_array($requirement, $this->requirements);
    }

    public function getFacilityServiceIdentifier(): string
    {
        return $this->facilityId . '|' . strtolower($this->name);
    }

    public function canBeRequestedBy(array $participantSkills): bool
    {
        // Check if participant has the required skill type
        return in_array($this->skillType->getValue(), $participantSkills);
    }
}
