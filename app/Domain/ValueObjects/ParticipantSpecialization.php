<?php

namespace App\Domain\ValueObjects;

class ParticipantSpecialization
{
    private const VALID_SPECIALIZATIONS = ['software', 'hardware', 'research', 'testing', 'project_management'];
    
    private string $value;

    public function __construct(string $value)
    {
        if (!in_array($value, self::VALID_SPECIALIZATIONS)) {
            throw new \InvalidArgumentException("Invalid specialization: {$value}");
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
            'software' => 'Software Development',
            'hardware' => 'Hardware Design',
            'research' => 'Research & Development',
            'testing' => 'Testing & Quality Assurance',
            'project_management' => 'Project Management',
        };
    }

    public static function getAllOptions(): array
    {
        return [
            'software' => 'Software Development',
            'hardware' => 'Hardware Design',
            'research' => 'Research & Development',
            'testing' => 'Testing & Quality Assurance',
            'project_management' => 'Project Management'
        ];
    }
}
