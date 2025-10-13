<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\FacilityRepositoryInterface;
use App\Domain\Entities\Facility;
use App\Application\Exceptions\FacilityNotFoundException;

class GetFacilityUseCase
{
    public function __construct(
        private FacilityRepositoryInterface $facilityRepository
    ) {}

    public function execute(string $facilityId): Facility
    {
        $facility = $this->facilityRepository->findById($facilityId);
        
        if (!$facility) {
            throw new FacilityNotFoundException("Facility with ID {$facilityId} not found");
        }

        return $facility;
    }
}
