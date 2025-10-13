<?php

namespace App\Application\UseCases;

use App\Application\DTOs\UpdateServiceDTO;
use App\Domain\Repositories\ServiceRepositoryInterface;
use App\Application\Exceptions\ServiceNotFoundException;

class UpdateServiceUseCase
{
    public function __construct(
        private ServiceRepositoryInterface $serviceRepository
    ) {}

    public function execute(UpdateServiceDTO $dto): void
    {
        $service = $this->serviceRepository->findById($dto->id);
        
        if (!$service) {
            throw new ServiceNotFoundException("Service with ID {$dto->id} not found");
        }

        // Check scoped uniqueness if name is being updated
        if ($dto->name !== null) {
            $existing = $this->serviceRepository->findByNameAndFacility($dto->name, $service->getFacilityId());
            if ($existing && $existing->getId() !== $dto->id) {
                throw new \DomainException('A service with this name already exists in this facility');
            }
        }

        // Prepare update data, only including non-null values
        $updateData = array_filter([
            'name' => $dto->name,
            'description' => $dto->description,
            'requirements' => $dto->requirements,
            'availability_status' => $dto->availabilityStatus,
            'cost' => $dto->cost,
        ], fn($value) => $value !== null);

        $service->update($updateData);
        
        $this->serviceRepository->save($service);
    }
}
