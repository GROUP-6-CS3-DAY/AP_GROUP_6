<?php

namespace App\Application\DTOs;

class CreateServiceDTO
{
    public function __construct(
        public readonly string $facilityId,
        public readonly string $name,
        public readonly string $description,
        public readonly string $category,
        public readonly string $skillType,
        public readonly array $requirements,
        public readonly string $availabilityStatus,
        public readonly float $cost
    ) {}
}
