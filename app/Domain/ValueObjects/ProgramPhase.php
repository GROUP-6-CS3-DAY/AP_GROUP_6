<?php

namespace App\Domain\ValueObjects;

class ProgramPhase
{
    private const VALID_PHASES = [
        'planning' => 'Planning',
        'inception' => 'Inception', 
        'development' => 'Development',
        'implementation' => 'Implementation',
        'monitoring' => 'Monitoring & Evaluation',
        'execution' => 'Execution',
        'expansion' => 'Expansion',
        'sustainability' => 'Sustainability',
        'transition' => 'Transition',
        'evaluation' => 'Evaluation',
        'closure' => 'Closure'
    ];
    
    private string $value;

    public function __construct(string $value)
    {
        $normalizedValue = strtolower(trim($value));


        
        if (!array_key_exists($normalizedValue, self::VALID_PHASES)) {
            throw new \InvalidArgumentException("Invalid program phase: {$value}");
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

    public function equals(ProgramPhase $other): bool
    {
        return $this->value === $other->value;
    }
}
