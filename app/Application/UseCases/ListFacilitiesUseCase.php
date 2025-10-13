<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\FacilityRepositoryInterface;

class ListFacilitiesUseCase
{
    public function __construct(
        private FacilityRepositoryInterface $facilityRepository
    ) {}

    public function execute(): array
    {
        return $this->facilityRepository->findAll();
    }
}
