<?php

namespace App\Application\DTOs;

class CreateEquipmentDTO
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
}
