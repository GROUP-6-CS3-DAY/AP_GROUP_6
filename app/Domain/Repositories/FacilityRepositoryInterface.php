<?php

namespace App\Domain\Repositories;

interface FacilityRepositoryInterface
{
    public function findById(string $id);
    public function findAll(): array;
    public function save($facility): void;
    public function delete(string $id): void;
}
