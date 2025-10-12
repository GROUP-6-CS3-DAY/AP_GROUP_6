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
            throw new \Exception('Equipment not found');
        }

        // Business rule: Equipment cannot be deleted if referenced by active projects
        $activeProjectsInFacility = $this->projectRepository->findActiveProjectsByFacilityId($equipment->getFacilityId());
        $equipment->validateDeletionSafety($activeProjectsInFacility);

        $this->equipmentRepository->delete($equipmentId);
    }
}
