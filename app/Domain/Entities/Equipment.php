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
        $this->validateBusinessRules($data);
        
        $this->facilityId = $data['facility_id'] ?? $this->facilityId;
        $this->name = $data['name'] ?? $this->name;
        $this->capabilities = $data['capabilities'] ?? $this->capabilities;
        $this->description = $data['description'] ?? $this->description;
        $this->inventoryCode = $data['inventory_code'] ?? $this->inventoryCode;
        $this->usageDomain = isset($data['usage_domain']) ? new UsageDomain($data['usage_domain']) : $this->usageDomain;
        $this->supportPhase = isset($data['support_phase']) ? new SupportPhase($data['support_phase']) : $this->supportPhase;
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
}
