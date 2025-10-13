<?php

namespace App\Domain\ValueObjects;

class SkillType
{
    private const VALID_TYPES = ['technical', 'analytical', 'creative', 'managerial', 'operational', 'specialized'];
    
    private string $value;

    public function __construct(string $value)
    {
        if (!in_array($value, self::VALID_TYPES)) {
            throw new \InvalidArgumentException("Invalid skill type: {$value}");
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
            'technical' => 'Technical Skills',
            'analytical' => 'Analytical Skills',
            'creative' => 'Creative Skills',
            'managerial' => 'Managerial Skills',
            'operational' => 'Operational Skills',
            'specialized' => 'Specialized Skills',
        };
    }

    public static function getAllOptions(): array
    {
        return [
            'technical' => 'Technical Skills',
            'analytical' => 'Analytical Skills',
            'creative' => 'Creative Skills',
            'managerial' => 'Managerial Skills',
            'operational' => 'Operational Skills',
            'specialized' => 'Specialized Skills'
        ];
    }
}
