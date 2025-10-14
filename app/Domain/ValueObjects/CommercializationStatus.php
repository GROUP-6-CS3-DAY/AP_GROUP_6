<?php

namespace App\Domain\ValueObjects;

class CommercializationStatus
{
    private const VALID_STATUSES = ['Not Applicable', 'Ready', 'In Progress', 'Commercialized', ''];
    
    private string $value;

    public function __construct(string $value)
    {
        // Allow empty string for optional status
        if (empty($value)) {
            $this->value = '';
            return;
        }
        
        if (!in_array($value, self::VALID_STATUSES)) {
            throw new \InvalidArgumentException("Invalid commercialization status: {$value}");
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
            'Not Applicable' => 'Not Applicable',
            'Ready' => 'Ready',
            'In Progress' => 'In Progress',
            'Commercialized' => 'Commercialized',
            '' => 'Not Set',
            default => $this->value
        };
    }

    public function isCommercializable(): bool
    {
        return in_array($this->value, ['Ready', 'In Progress', 'Commercialized']);
    }

    public static function getAllOptions(): array
    {
        return [
            'Not Applicable' => 'Not Applicable',
            'Ready' => 'Ready',
            'In Progress' => 'In Progress',
            'Commercialized' => 'Commercialized'
        ];
    }
}
