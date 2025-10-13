<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\FacilityRepositoryInterface;
use App\Application\Exceptions\FacilityNotFoundException;

class DeleteFacilityUseCase
{
    public function __construct(
        private FacilityRepositoryInterface $facilityRepository
    ) {}

    public function execute(string $facilityId): void
    {
        $facility = $this->facilityRepository->findById($facilityId);
        
        if (!$facility) {
            throw new FacilityNotFoundException("Facility with ID {$facilityId} not found");
        }

        // The deletion constraint is handled in the repository
        $this->facilityRepository->delete($facilityId);
    }
}
