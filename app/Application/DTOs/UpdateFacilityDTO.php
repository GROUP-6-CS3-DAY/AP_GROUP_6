<?php

namespace App\Application\DTOs;

class UpdateFacilityDTO
{
    public function __construct(
        public readonly string $id,
        public readonly ?string $name = null,
        public readonly ?string $description = null,
        public readonly ?string $location = null,
        public readonly ?string $facilityType = null,
        public readonly ?int $capacity = null,
        public readonly ?array $equipmentList = null,
        public readonly ?array $capabilities = null,
        public readonly ?string $availabilityStatus = null
    ) {}
}
