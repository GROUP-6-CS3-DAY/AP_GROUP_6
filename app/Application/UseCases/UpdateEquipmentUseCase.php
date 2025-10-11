<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\EquipmentRepositoryInterface;
use App\Application\DTOs\UpdateEquipmentDTO;

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

        $equipment->update($dto->toArray());
        $this->equipmentRepository->save($equipment);
    }
}
