<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\EquipmentRepositoryInterface;
use App\Application\DTOs\UpdateEquipmentDTO;
use App\Domain\Entities\Equipment;

class UpdateEquipmentUseCase
{
    private EquipmentRepositoryInterface $equipmentRepository;

    public function __construct(EquipmentRepositoryInterface $equipmentRepository)
    {
        $this->equipmentRepository = $equipmentRepository;
    }

    public function execute(string $equipmentId, UpdateEquipmentDTO $dto): void
    {
        $equipment = $this->equipmentRepository->findById($equipmentId);
        
        if (!$equipment) {
            throw new \Exception('Equipment not found');
        }

        // Business rule: Check inventory code uniqueness if it's being changed
        if ($dto->inventoryCode !== $equipment->getInventoryCode()) {
            $existingInventoryCodes = $this->equipmentRepository->findAllInventoryCodes($equipmentId);
            
            // Create temporary equipment with new inventory code to validate uniqueness
            $tempEquipment = new Equipment(
                id: $equipment->getId(),
                facilityId: $dto->facilityId,
                name: $dto->name,
                capabilities: $dto->capabilities,
                description: $dto->description,
                inventoryCode: $dto->inventoryCode,
                usageDomain: new \App\Domain\ValueObjects\UsageDomain($dto->usageDomain),
                supportPhase: new \App\Domain\ValueObjects\SupportPhase($dto->supportPhase)
            );
            
            $tempEquipment->validateInventoryCodeUniqueness($existingInventoryCodes);
        }

        $equipment->update($dto->toArray());
        $this->equipmentRepository->save($equipment);
    }
}
