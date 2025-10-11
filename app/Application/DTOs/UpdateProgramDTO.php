<?php

namespace App\Application\DTOs;

class UpdateProgramDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public readonly string $nationalAlignment,
        public readonly array $focusAreas,
        public readonly array $phases
    ) {}

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'national_alignment' => $this->nationalAlignment,
            'focus_areas' => $this->focusAreas,
            'phases' => $this->phases,
        ];
    }
}
