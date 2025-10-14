<?php

namespace App\Domain\ValueObjects;

class ParticipantAffiliation
{
    private const VALID_AFFILIATIONS = ['cs', 'ee', 'me', 'ce', 'other'];
    
    private string $value;

    public function __construct(string $value)
    {
        if (!in_array($value, self::VALID_AFFILIATIONS)) {
            throw new \InvalidArgumentException("Invalid affiliation: {$value}");
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
            'cs' => 'Computer Science',
            'ee' => 'Electrical Engineering',
            'me' => 'Mechanical Engineering',
            'ce' => 'Civil Engineering',
            'other' => 'Other',
        };
    }

    public static function getAllOptions(): array
    {
        return [
            'cs' => 'Computer Science',
            'ee' => 'Electrical Engineering',
            'me' => 'Mechanical Engineering',
            'ce' => 'Civil Engineering',
            'other' => 'Other'
        ];
    }
}
