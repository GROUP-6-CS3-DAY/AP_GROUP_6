<?php

namespace App\Domain\ValueObjects;

class ServiceCategory
{
    private const VALID_CATEGORIES = ['testing', 'manufacturing', 'design', 'consulting', 'training', 'maintenance', 'calibration'];
    
    private string $value;

    public function __construct(string $value)
    {
        if (!in_array($value, self::VALID_CATEGORIES)) {
            throw new \InvalidArgumentException("Invalid service category: {$value}");
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
            'testing' => 'Testing Services',
            'manufacturing' => 'Manufacturing Services',
            'design' => 'Design Services',
            'consulting' => 'Consulting Services',
            'training' => 'Training Services',
            'maintenance' => 'Maintenance Services',
            'calibration' => 'Calibration Services',
        };
    }

    public static function getAllOptions(): array
    {
        return [
            'testing' => 'Testing Services',
            'manufacturing' => 'Manufacturing Services',
            'design' => 'Design Services',
            'consulting' => 'Consulting Services',
            'training' => 'Training Services',
            'maintenance' => 'Maintenance Services',
            'calibration' => 'Calibration Services'
        ];
    }
}
