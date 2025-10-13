<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\ServiceRepositoryInterface;
use App\Application\Exceptions\ServiceNotFoundException;

class DeleteServiceUseCase
{
    public function __construct(
        private ServiceRepositoryInterface $serviceRepository
    ) {}

    public function execute(string $serviceId): void
    {
        $service = $this->serviceRepository->findById($serviceId);
        
        if (!$service) {
            throw new ServiceNotFoundException("Service with ID {$serviceId} not found");
        }

        // Check delete guard - service cannot be deleted if referenced by project testing requirements
        if ($this->serviceRepository->isReferencedByProjectTestingRequirements($serviceId)) {
            throw new \DomainException('Service in use by Project testing requirements');
        }

        $this->serviceRepository->delete($serviceId);
    }
}
