<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Outcome;

interface OutcomeRepositoryInterface
{
    public function findById(string $id): ?Outcome;
    public function save(Outcome $outcome): void;
    public function delete(string $id): void;
    public function findAll(): array;
}
