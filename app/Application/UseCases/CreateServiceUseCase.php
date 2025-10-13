<?php

namespace App\Application\UseCases;

use App\Application\DTOs\CreateServiceDTO;
use App\Domain\Repositories\ServiceRepositoryInterface;
use App\Domain\Repositories\FacilityRepositoryInterface;
use App\Domain\Entities\Service;
use App\Domain\ValueObjects\ServiceCategory;
use App\Domain\ValueObjects\SkillType;
use App\Application\Exceptions\FacilityNotFoundException;
use Illuminate\Support\Str;

class CreateServiceUseCase
{
    public function __construct(
        private ServiceRepositoryInterface $serviceRepository,
        private FacilityRepositoryInterface $facilityRepository
    ) {}

    public function execute(CreateServiceDTO $dto): string
    {
        // Verify facility exists
        $facility = $this->facilityRepository->findById($dto->facilityId);
        if (!$facility) {
            throw new FacilityNotFoundException("Facility with ID {$dto->facilityId} not found");
        }

        // Check for scoped uniqueness (service name within facility)
        $existing = $this->serviceRepository->findByNameAndFacility($dto->name, $dto->facilityId);
        if ($existing) {
            throw new \DomainException('A service with this name already exists in this facility');
        }

        $serviceId = Str::uuid()->toString();
        
        $service = new Service(
            id: $serviceId,
            facilityId: $dto->facilityId,
            name: $dto->name,
            description: $dto->description,
            category: new ServiceCategory($dto->category),
            skillType: new SkillType($dto->skillType),
            requirements: $dto->requirements,
            availabilityStatus: $dto->availabilityStatus,
            cost: $dto->cost
        );

        $this->serviceRepository->save($service);

        return $serviceId;
    }
}
