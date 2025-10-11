<?php

namespace App\Domain\Repositories;

interface ProgramRepositoryInterface
{
    public function findById(string $id);
    public function findAll(): array;
    public function save($program): void;
    public function delete(string $id): void;
}
