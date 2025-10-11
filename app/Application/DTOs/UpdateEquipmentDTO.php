<?php

namespace App\Application\DTOs;

class UpdateEquipmentDTO
{
    public function __construct(
        public readonly string $facilityId,
        public readonly string $name,
        public readonly array $capabilities,
        public readonly string $description,
        public readonly string $inventoryCode,
        public readonly string $usageDomain,
        public readonly string $supportPhase
    ) {}

    public function toArray(): array
    {
        return [
            'facility_id' => $this->facilityId,
            'name' => $this->name,
            'capabilities' => $this->capabilities,
            'description' => $this->description,
            'inventory_code' => $this->inventoryCode,
            'usage_domain' => $this->usageDomain,
            'support_phase' => $this->supportPhase,
        ];
    }
}
