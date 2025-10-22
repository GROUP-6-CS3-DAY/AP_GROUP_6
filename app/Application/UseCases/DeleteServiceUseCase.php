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

        // Deletion guards
        $this->validateServiceCanBeDeleted($service, $serviceId);

        $this->serviceRepository->delete($serviceId);
    }

    private function validateServiceCanBeDeleted($service, string $serviceId): void
    {
        // Deletion guard: Service cannot be deleted if referenced by project testing requirements
        if ($this->serviceRepository->isReferencedByProjectTestingRequirements($serviceId)) {
            throw new \DomainException('Cannot delete service that is referenced by active project testing requirements. Remove from projects first.');
        }

        // Deletion guard: Cannot delete services with active bookings/usage
        if ($this->serviceRepository->hasActiveBookings($serviceId)) {
            throw new \DomainException('Cannot delete service with active bookings or ongoing usage. Cancel all bookings first.');
        }

        // Deletion guard: Check service status
        if (method_exists($service, 'getStatus')) {
            $restrictedStatuses = ['in_use', 'booked', 'reserved'];
            if (in_array($service->getStatus()->getValue(), $restrictedStatuses)) {
                throw new \DomainException('Cannot delete service with status: ' . $service->getStatus()->getValue() . '. Change status first.');
            }
        }

        // Deletion guard: Check if service is critical/core service
        if (method_exists($service, 'isCritical') && $service->isCritical()) {
            throw new \DomainException('Cannot delete critical services. Contact system administrator.');
        }
    }
}
