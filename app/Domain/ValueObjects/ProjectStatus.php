<?php

namespace App\Domain\ValueObjects;

class ProjectStatus
{
    private const VALID_STATUSES = [
        'planning' => 'Planning',
        'active' => 'Active',
        'on_hold' => 'On Hold',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled'
    ];
    
    private string $value;

    public function __construct(string $value)
    {
        $normalizedValue = strtolower(trim($value));
        
        if (!array_key_exists($normalizedValue, self::VALID_STATUSES)) {
            throw new \InvalidArgumentException("Invalid project status: {$value}");
        }
        
        $this->value = $normalizedValue;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getDisplayName(): string
    {
        return self::VALID_STATUSES[$this->value];
    }

    public static function getAllOptions(): array
    {
        return self::VALID_STATUSES;
    }

    public function equals(ProjectStatus $other): bool
    {
        return $this->value === $other->value;
    }

    public function canTransitionTo(ProjectStatus $newStatus): bool
    {
        $allowedTransitions = [
            'planning' => ['active', 'cancelled'],
            'active' => ['on_hold', 'completed', 'cancelled'],
            'on_hold' => ['active', 'cancelled'],
            'completed' => [],
            'cancelled' => []
        ];

        return in_array($newStatus->getValue(), $allowedTransitions[$this->value] ?? []);
    }
}
