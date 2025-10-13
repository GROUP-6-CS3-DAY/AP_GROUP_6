<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\FacilityRepositoryInterface;

class ListFacilitiesUseCase
{
    public function __construct(
        private FacilityRepositoryInterface $facilityRepository
    ) {}

    public function execute(array $filters = []): array
    {
        // For now, return all facilities
        // In a real implementation, you'd apply filters here
        return $this->facilityRepository->findAll();
    }

    public function getStatistics(): array
    {
        // Basic statistics - implement according to your needs
        $facilities = $this->facilityRepository->findAll();
        
        return [
            'total_facilities' => count($facilities),
            'by_type' => [],
            'by_partner' => [],
            'total_services' => 0,
            'total_equipment' => 0,
        ];
    }
}
