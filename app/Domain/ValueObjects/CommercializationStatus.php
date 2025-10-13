<?php

namespace App\Domain\ValueObjects;

class CommercializationStatus
{
    private const VALID_STATUSES = ['not_applicable', 'not_ready', 'ready', 'in_progress', 'commercialized'];
    
    private string $value;

    public function __construct(string $value)
    {
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
            'not_applicable' => 'Not Applicable',
            'not_ready' => 'Not Ready',
            'ready' => 'Ready',
            'in_progress' => 'In Progress',
            'commercialized' => 'Commercialized',
        };
    }

    public function isCommercializable(): bool
    {
        return in_array($this->value, ['ready', 'in_progress', 'commercialized']);
    }

    public static function getAllOptions(): array
    {
        return [
            'not_applicable' => 'Not Applicable',
            'not_ready' => 'Not Ready',
            'ready' => 'Ready',
            'in_progress' => 'In Progress',
            'commercialized' => 'Commercialized'
        ];
    }
}
