<?php

namespace App\Application\DTOs;

class CreateProgramDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public readonly string $nationalAlignment,
        public readonly array $focusAreas,
        public readonly array $phases
    ) {}
}
