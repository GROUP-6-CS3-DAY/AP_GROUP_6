<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Service;

interface ServiceRepositoryInterface
{
    public function findById(string $id): ?Service;
    public function findAll(): array;
    public function findByFacilityId(string $facilityId): array;
    public function save(Service $service): void;
    public function delete(string $id): void;
    public function findByNameAndFacility(string $name, string $facilityId): ?Service;
    public function isReferencedByProjectTestingRequirements(string $serviceId): bool;
}
