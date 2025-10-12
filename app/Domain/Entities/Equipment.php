<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\UsageDomain;
use App\Domain\ValueObjects\SupportPhase;

class Equipment
{
    private string $id;
    private string $facilityId;
    private string $name;
    private array $capabilities;
    private string $description;
    private string $inventoryCode;
    private UsageDomain $usageDomain;
    private SupportPhase $supportPhase;

    public function __construct(
        string $id,
        string $facilityId,
        string $name,
        array $capabilities,
        string $description,
        string $inventoryCode,
        UsageDomain $usageDomain,
        SupportPhase $supportPhase
    ) {
        $this->validateRequiredFields($facilityId, $name, $inventoryCode);
        $this->validateUsageDomainSupportPhaseCoherence($usageDomain, $supportPhase);
        
        $this->id = $id;
        $this->facilityId = $facilityId;
        $this->name = $name;
        $this->capabilities = $capabilities;
        $this->description = $description;
        $this->inventoryCode = $inventoryCode;
        $this->usageDomain = $usageDomain;
        $this->supportPhase = $supportPhase;
    }

    // Getters
    public function getId(): string { return $this->id; }
    public function getFacilityId(): string { return $this->facilityId; }
    public function getName(): string { return $this->name; }
    public function getCapabilities(): array { return $this->capabilities; }
    public function getDescription(): string { return $this->description; }
    public function getInventoryCode(): string { return $this->inventoryCode; }
    public function getUsageDomain(): UsageDomain { return $this->usageDomain; }
    public function getSupportPhase(): SupportPhase { return $this->supportPhase; }

    // Business logic methods
    public function update(array $data): void
    {
        $facilityId = $data['facility_id'] ?? $this->facilityId;
        $name = $data['name'] ?? $this->name;
        $inventoryCode = $data['inventory_code'] ?? $this->inventoryCode;
        $usageDomain = isset($data['usage_domain']) ? new UsageDomain($data['usage_domain']) : $this->usageDomain;
        $supportPhase = isset($data['support_phase']) ? new SupportPhase($data['support_phase']) : $this->supportPhase;
        
        $this->validateRequiredFields($facilityId, $name, $inventoryCode);
        $this->validateBusinessRules($data);
        $this->validateUsageDomainSupportPhaseCoherence($usageDomain, $supportPhase);
        
        $this->facilityId = $facilityId;
        $this->name = $name;
        $this->capabilities = $data['capabilities'] ?? $this->capabilities;
        $this->description = $data['description'] ?? $this->description;
        $this->inventoryCode = $inventoryCode;
        $this->usageDomain = $usageDomain;
        $this->supportPhase = $supportPhase;
    }

    private function validateRequiredFields(string $facilityId, string $name, string $inventoryCode): void
    {
        if (empty($facilityId) || empty($name) || empty($inventoryCode)) {
            throw new \DomainException('Equipment.FacilityId, Equipment.Name, and Equipment.InventoryCode are required');
        }
    }

    private function validateBusinessRules(array $data): void
    {
        if (isset($data['name']) && strlen($data['name']) < 3) {
            throw new \DomainException('Equipment name must be at least 3 characters long');
        }

        if (isset($data['capabilities']) && empty($data['capabilities'])) {
            throw new \DomainException('Equipment must have at least one capability');
        }

        if (isset($data['inventory_code']) && strlen($data['inventory_code']) < 3) {
            throw new \DomainException('Inventory code must be at least 3 characters long');
        }
    }

    private function validateUsageDomainSupportPhaseCoherence(UsageDomain $usageDomain, SupportPhase $supportPhase): void
    {
        // Business rule: Electronics equipment must support Prototyping or Testing
        if ($usageDomain->getValue() === 'electronics') {
            $supportPhaseValue = $supportPhase->getValue();
            $allowedPhases = ['prototyping', 'testing'];
            
            if (!in_array($supportPhaseValue, $allowedPhases)) {
                throw new \DomainException('Electronics equipment must support Prototyping or Testing');
            }
        }
    }

    public function validateInventoryCodeUniqueness(array $existingInventoryCodes): void
    {
        // Business rule: Inventory code must be unique across all equipment
        $normalizedCode = strtolower(trim($this->inventoryCode));
        $normalizedExistingCodes = array_map(fn($code) => strtolower(trim($code)), $existingInventoryCodes);
        
        if (in_array($normalizedCode, $normalizedExistingCodes)) {
            throw new \DomainException('Equipment.InventoryCode already exists');
        }
    }

    public function validateDeletionSafety(array $activeProjectsInFacility): void
    {
        // Business rule: Equipment cannot be deleted if referenced by active projects
        if (!empty($activeProjectsInFacility)) {
            foreach ($activeProjectsInFacility as $project) {
                // Check if this equipment is referenced by any active project
                if ($this->isReferencedByProject($project)) {
                    throw new \DomainException('Equipment referenced by active Project');
                }
            }
        }
    }

    private function isReferencedByProject(array $project): bool
    {
        // Check if equipment ID or inventory code is referenced in project's technical requirements
        $technicalRequirements = $project['technical_requirements'] ?? [];
        $equipmentReferences = $project['equipment_ids'] ?? [];
        
        return in_array($this->id, $equipmentReferences) || 
               in_array($this->inventoryCode, $technicalRequirements);
    }

    public function hasCapability(string $capability): bool
    {
        return in_array($capability, $this->capabilities);
    }

    public function getCapabilitiesAsString(): string
    {
        return implode(', ', $this->capabilities);
    }

    public function isAvailableForPhase(string $phase): bool
    {
        $availablePhases = [
            'training' => ['training', 'research'],
            'prototyping' => ['prototyping', 'testing', 'research'],
            'testing' => ['testing', 'prototyping'],
            'commercialization' => ['commercialization', 'testing'],
            'research' => ['research', 'training', 'prototyping']
        ];

        return in_array($phase, $availablePhases[$this->supportPhase->getValue()] ?? []);
    }

    public function canSupportElectronicsWork(): bool
    {
        return $this->usageDomain->getValue() === 'electronics' && 
               in_array($this->supportPhase->getValue(), ['prototyping', 'testing']);
    }
}
