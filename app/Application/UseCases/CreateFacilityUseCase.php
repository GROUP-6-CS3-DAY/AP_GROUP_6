<?php

namespace App\Application\UseCases;

use App\Application\DTOs\CreateFacilityDTO;
use App\Domain\Repositories\FacilityRepositoryInterface;
use App\Domain\Entities\Facility;
use App\Domain\ValueObjects\FacilityType;
use Illuminate\Support\Str;

class CreateFacilityUseCase
{
    public function __construct(
        private FacilityRepositoryInterface $facilityRepository
    ) {}

    public function execute(CreateFacilityDTO $dto): string
    {
        // Check for uniqueness constraint
        $existing = $this->facilityRepository->findByNameAndLocation($dto->name, $dto->location);
        if ($existing) {
            throw new \DomainException('A facility with this name already exists at this location');
        }

        $facilityId = Str::uuid()->toString();
        
        $facility = new Facility(
            id: $facilityId,
            name: $dto->name,
            description: $dto->description,
            location: $dto->location,
            facilityType: new FacilityType($dto->facilityType),
            capacity: $dto->capacity,
            equipmentList: $dto->equipmentList,
            capabilities: $dto->capabilities,
            availabilityStatus: $dto->availabilityStatus
        );

        $this->facilityRepository->save($facility);

        return $facilityId;
    }
}
