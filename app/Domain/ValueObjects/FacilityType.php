<?php

namespace App\Domain\ValueObjects;

class FacilityType
{
    private const VALID_TYPES = ['laboratory', 'workshop', 'office', 'manufacturing', 'storage', 'testing', 'research'];
    
    private string $value;

    public function __construct(string $value)
    {
        if (!in_array($value, self::VALID_TYPES)) {
            throw new \InvalidArgumentException("Invalid facility type: {$value}");
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
            'laboratory' => 'Laboratory',
            'workshop' => 'Workshop',
            'office' => 'Office',
            'manufacturing' => 'Manufacturing',
            'storage' => 'Storage',
            'testing' => 'Testing Facility',
            'research' => 'Research Facility',
        };
    }

    public static function getAllOptions(): array
    {
        return [
            'laboratory' => 'Laboratory',
            'workshop' => 'Workshop',
            'office' => 'Office',
            'manufacturing' => 'Manufacturing',
            'storage' => 'Storage',
            'testing' => 'Testing Facility',
            'research' => 'Research Facility'
        ];
    }
}
