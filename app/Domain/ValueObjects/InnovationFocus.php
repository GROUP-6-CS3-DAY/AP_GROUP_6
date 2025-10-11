<?php

namespace App\Domain\ValueObjects;

class InnovationFocus
{
    private const VALID_TYPES = ['product', 'process', 'technology', 'service'];
    
    private string $value;

    public function __construct(string $value)
    {
        if (!in_array($value, self::VALID_TYPES)) {
            throw new \InvalidArgumentException("Invalid innovation focus: {$value}");
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
            'product' => 'Product Innovation',
            'process' => 'Process Innovation',
            'technology' => 'Technology Innovation',
            'service' => 'Service Innovation',
        };
    }

    public static function getAllOptions(): array
    {
        return [
            'product' => 'Product Innovation',
            'process' => 'Process Innovation',
            'technology' => 'Technology Innovation',
            'service' => 'Service Innovation'
        ];
    }
}
