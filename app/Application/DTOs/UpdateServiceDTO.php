<?php

namespace App\Application\DTOs;

class UpdateServiceDTO
{
    public function __construct(
        public readonly string $id,
        public readonly ?string $name = null,
        public readonly ?string $description = null,
        public readonly ?array $requirements = null,
        public readonly ?string $availabilityStatus = null,
        public readonly ?float $cost = null
    ) {}
}
