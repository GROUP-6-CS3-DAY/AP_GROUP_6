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
            'phases' => $this->phases
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            description: $data['description'],
            nationalAlignment: $data['national_alignment'],
            focusAreas: is_array($data['focus_areas']) ? $data['focus_areas'] : explode(',', $data['focus_areas']),
            phases: is_array($data['phases']) ? $data['phases'] : explode(',', $data['phases'])
        );
    }
}
