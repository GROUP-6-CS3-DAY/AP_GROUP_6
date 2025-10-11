<?php

namespace App\Domain\ValueObjects;

class SupportPhase
{
    private const VALID_PHASES = [
        'training' => 'Training',
        'prototyping' => 'Prototyping',
        'testing' => 'Testing',
        'commercialization' => 'Commercialization',
        'research' => 'Research'
    ];
    
    private string $value;

    public function __construct(string $value)
    {
        $normalizedValue = strtolower(trim($value));
        
        if (!array_key_exists($normalizedValue, self::VALID_PHASES)) {
            throw new \InvalidArgumentException("Invalid support phase: {$value}");
        }
        
        $this->value = $normalizedValue;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getDisplayName(): string
    {
        return self::VALID_PHASES[$this->value];
    }

    public static function getAllOptions(): array
    {
        return self::VALID_PHASES;
    }

    public function equals(SupportPhase $other): bool
    {
        return $this->value === $other->value;
    }
}
