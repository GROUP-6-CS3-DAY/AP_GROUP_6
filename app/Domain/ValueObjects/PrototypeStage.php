<?php

namespace App\Domain\ValueObjects;

class PrototypeStage
{
    private const VALID_STAGES = ['concept', 'design', 'prototype', 'testing', 'production'];
    
    private string $value;

    public function __construct(string $value)
    {
        if (!in_array($value, self::VALID_STAGES)) {
            throw new \InvalidArgumentException("Invalid prototype stage: {$value}");
        }
        
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getDisplayName(): string
    {
        return match($this->value) {
            'concept' => 'Concept',
            'design' => 'Design',
            'prototype' => 'Prototype',
            'testing' => 'Testing',
            'production' => 'Production Ready',
        };
    }

    public function canAdvance(): bool
    {
        return $this->value !== 'production';
    }

    public static function getAllOptions(): array
    {
        return [
            'concept' => 'Concept',
            'design' => 'Design',
            'prototype' => 'Prototype',
            'testing' => 'Testing',
            'production' => 'Production Ready'
        ];
    }
}
