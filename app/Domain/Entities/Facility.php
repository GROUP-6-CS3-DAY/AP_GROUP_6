<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\FacilityType;

class Facility
{
    private string $id;
    private string $name;
    private string $description;
    private string $location;
    private FacilityType $facilityType;
    private int $capacity;
    private array $equipmentList;
    private array $capabilities;
    private string $availabilityStatus;

    public function __construct(
        string $id,
        string $name,
        string $description,
        string $location,
        FacilityType $facilityType,
        int $capacity = 0,
        array $equipmentList = [],
        array $capabilities = [],
        string $availabilityStatus = 'available'
    ) {
        $this->validateRequiredFields($name, $location);
        $this->validateBusinessRules($capabilities, $equipmentList);
        
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->location = $location;
        $this->facilityType = $facilityType;
        $this->capacity = $capacity;
        $this->equipmentList = $equipmentList;
        $this->capabilities = $capabilities;
        $this->availabilityStatus = $availabilityStatus;
    }

    // Getters
    public function getId(): string { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getDescription(): string { return $this->description; }
    public function getLocation(): string { return $this->location; }
    public function getFacilityType(): FacilityType { return $this->facilityType; }
    public function getCapacity(): int { return $this->capacity; }
    public function getEquipmentList(): array { return $this->equipmentList; }
    public function getCapabilities(): array { return $this->capabilities; }
    public function getAvailabilityStatus(): string { return $this->availabilityStatus; }

    public function update(array $data): void
    {
        $newCapabilities = $data['capabilities'] ?? $this->capabilities;
        $newEquipmentList = $data['equipment_list'] ?? $this->equipmentList;
        
        $this->validateBusinessRules($newCapabilities, $newEquipmentList);
        
        $this->name = $data['name'] ?? $this->name;
        $this->description = $data['description'] ?? $this->description;
        $this->location = $data['location'] ?? $this->location;
        $this->capacity = $data['capacity'] ?? $this->capacity;
        $this->equipmentList = $newEquipmentList;
        $this->capabilities = $newCapabilities;
        $this->availabilityStatus = $data['availability_status'] ?? $this->availabilityStatus;
    }

    private function validateRequiredFields(string $name, string $location): void
    {
        if (empty($name)) {
            throw new \DomainException('Facility.Name is required');
        }
        
        if (empty($location)) {
            throw new \DomainException('Facility.Location is required');
        }
    }

    private function validateBusinessRules(array $capabilities, array $equipmentList): void
    {
        // Business rule: Capabilities must contain at least one capability if any Services or Equipment exist
        if (!empty($equipmentList) && empty($capabilities)) {
            throw new \DomainException('Facility.Capabilities must be populated when Equipment exist');
        }
    }

    // Domain behavior methods
    public function hasEquipment(): bool
    {
        return !empty($this->equipmentList);
    }

    public function hasCapabilities(): bool
    {
        return !empty($this->capabilities);
    }

    public function isAvailable(): bool
    {
        return $this->availabilityStatus === 'available';
    }

    public function canAccommodate(int $requiredCapacity): bool
    {
        return $this->capacity >= $requiredCapacity && $this->isAvailable();
    }

    public function hasCapability(string $capability): bool
    {
        return in_array($capability, $this->capabilities);
    }

    public function getLocationIdentifier(): string
    {
        return strtolower($this->name . '|' . $this->location);
    }
}
