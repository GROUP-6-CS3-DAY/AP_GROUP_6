<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Facility;

interface FacilityRepositoryInterface
{
    public function findById(string $id): ?Facility;
    public function findAll(): array;
    public function save(Facility $facility): void;
    public function delete(string $id): void;
    public function findByNameAndLocation(string $name, string $location): ?Facility;
    public function hasDependentRecords(string $id): bool;
}
