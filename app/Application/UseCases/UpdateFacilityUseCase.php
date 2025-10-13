<?php

namespace App\Application\UseCases;

use App\Application\DTOs\UpdateFacilityDTO;
use App\Domain\Repositories\FacilityRepositoryInterface;
use App\Application\Exceptions\FacilityNotFoundException;

class UpdateFacilityUseCase
{
    public function __construct(
        private FacilityRepositoryInterface $facilityRepository
    ) {}

    public function execute(UpdateFacilityDTO $dto): void
    {
        $facility = $this->facilityRepository->findById($dto->id);
        
        if (!$facility) {
            throw new FacilityNotFoundException("Facility with ID {$dto->id} not found");
        }

        // Prepare update data, only including non-null values
        $updateData = array_filter([
            'name' => $dto->name,
            'description' => $dto->description,
            'location' => $dto->location,
            'capacity' => $dto->capacity,
            'equipment_list' => $dto->equipmentList,
            'capabilities' => $dto->capabilities,
            'availability_status' => $dto->availabilityStatus,
        ], fn($value) => $value !== null);

        $facility->update($updateData);
        
        $this->facilityRepository->save($facility);
    }
}
