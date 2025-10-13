<?php

namespace App\Application\DTOs;

class CreateFacilityDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public readonly string $location,
        public readonly string $facilityType,
        public readonly int $capacity,
        public readonly array $equipmentList,
        public readonly array $capabilities,
        public readonly string $availabilityStatus
    ) {}
}
