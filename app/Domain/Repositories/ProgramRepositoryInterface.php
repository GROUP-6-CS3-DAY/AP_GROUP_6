<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Program;

interface ProgramRepositoryInterface
{
    public function findById(string $id): ?Program;
    public function findAll(): array;
    public function save(Program $program): void;
    public function delete(string $id): void;
}
