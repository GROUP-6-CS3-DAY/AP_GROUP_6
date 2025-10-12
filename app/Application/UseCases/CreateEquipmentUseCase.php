<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\EquipmentRepositoryInterface;
use App\Application\DTOs\CreateEquipmentDTO;
use App\Domain\Entities\Equipment;
use App\Domain\ValueObjects\UsageDomain;
use App\Domain\ValueObjects\SupportPhase;
use Illuminate\Support\Str;

class CreateEquipmentUseCase
{
    private EquipmentRepositoryInterface $equipmentRepository;

    public function __construct(EquipmentRepositoryInterface $equipmentRepository)
    {
        $this->equipmentRepository = $equipmentRepository;
    }

    public function execute(CreateEquipmentDTO $dto): string
    {
        // Business rule: Check inventory code uniqueness
        $existingInventoryCodes = $this->equipmentRepository->findAllInventoryCodes();
        
        // Create equipment temporarily to validate inventory code uniqueness
        $equipment = new Equipment(
            id: Str::uuid()->toString(),
            facilityId: $dto->facilityId,
            name: $dto->name,
            capabilities: $dto->capabilities,
            description: $dto->description,
            inventoryCode: $dto->inventoryCode,
            usageDomain: new UsageDomain($dto->usageDomain),
            supportPhase: new SupportPhase($dto->supportPhase)
        );

        $equipment->validateInventoryCodeUniqueness($existingInventoryCodes);

        $this->equipmentRepository->save($equipment);
        
        return $equipment->getId();
    }
}
