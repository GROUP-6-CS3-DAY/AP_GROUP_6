<?php

namespace App\Domain\ValueObjects;

class OutcomeType
{
    private const VALID_TYPES = ['research', 'product', 'service', 'process', 'publication', 'patent', 'prototype'];
    
    private string $value;

    public function __construct(string $value)
    {
        if (!in_array($value, self::VALID_TYPES)) {
            throw new \InvalidArgumentException("Invalid outcome type: {$value}");
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
            'research' => 'Research Output',
            'product' => 'Product',
            'service' => 'Service',
            'process' => 'Process Innovation',
            'publication' => 'Publication',
            'patent' => 'Patent',
            'prototype' => 'Prototype',
        };
    }

    public function isCommercializable(): bool
    {
        return in_array($this->value, ['product', 'service', 'patent', 'prototype']);
    }

    public static function getAllOptions(): array
    {
        return [
            'research' => 'Research Output',
            'product' => 'Product',
            'service' => 'Service',
            'process' => 'Process Innovation',
            'publication' => 'Publication',
            'patent' => 'Patent',
            'prototype' => 'Prototype'
        ];
    }
}
