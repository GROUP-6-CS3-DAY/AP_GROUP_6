<?php

namespace App\Domain\ValueObjects;

class UsageDomain
{
    private const VALID_DOMAINS = [
        'electronics' => 'Electronics',
        'mechanical' => 'Mechanical',
        'iot' => 'IoT',
        'software' => 'Software',
        'renewable_energy' => 'Renewable Energy',
        'automation' => 'Automation',
        'materials' => 'Materials',
        'biomedical' => 'Biomedical'
    ];
    
    private string $value;

    public function __construct(string $value)
    {
        $normalizedValue = strtolower(trim($value));
        
        if (!array_key_exists($normalizedValue, self::VALID_DOMAINS)) {
            throw new \InvalidArgumentException("Invalid usage domain: {$value}");
        }
        
        $this->value = $normalizedValue;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getDisplayName(): string
    {
        return self::VALID_DOMAINS[$this->value];
    }

    public static function getAllOptions(): array
    {
        return self::VALID_DOMAINS;
    }

    public function equals(UsageDomain $other): bool
    {
        return $this->value === $other->value;
    }
}
