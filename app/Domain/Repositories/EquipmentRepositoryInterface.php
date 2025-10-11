<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Equipment;

interface EquipmentRepositoryInterface
{
    public function findById(string $id): ?Equipment;
    public function findWithFilters(array $filters, int $perPage = 15): array;
    public function findByFacility(string $facilityId): array;
    public function save(Equipment $equipment): void;
    public function delete(string $id): void;
}
