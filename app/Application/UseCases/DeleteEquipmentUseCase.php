<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\EquipmentRepositoryInterface;
use App\Domain\Repositories\ProjectRepositoryInterface;

class DeleteEquipmentUseCase
{
    private EquipmentRepositoryInterface $equipmentRepository;
    private ProjectRepositoryInterface $projectRepository;

    public function __construct(
        EquipmentRepositoryInterface $equipmentRepository,
        ProjectRepositoryInterface $projectRepository
    ) {
        $this->equipmentRepository = $equipmentRepository;
        $this->projectRepository = $projectRepository;
    }

    public function execute(string $equipmentId): void
    {
        $equipment = $this->equipmentRepository->findById($equipmentId);
        
        if (!$equipment) {
            throw new \DomainException('Equipment not found');
        }

        // Deletion guard: Equipment cannot be deleted if referenced by active projects
        $activeProjectsInFacility = $this->projectRepository->findActiveProjectsByFacilityId($equipment->getFacilityId());
        $equipment->validateDeletionSafety($activeProjectsInFacility);

        // Additional deletion guard: Check equipment status
        if ($equipment->getStatus()->getValue() === 'in_use') {
            throw new \DomainException('Cannot delete equipment that is currently in use. Change status first.');
        }

        // Deletion guard: Check if equipment is operational
        if ($equipment->getStatus()->getValue() === 'operational') {
            throw new \DomainException('Cannot delete operational equipment. Mark as decommissioned or under_maintenance first.');
        }

        $this->equipmentRepository->delete($equipmentId);
    }
}
