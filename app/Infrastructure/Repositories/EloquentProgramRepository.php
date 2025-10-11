<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\ProgramRepositoryInterface;
use App\Models\Program;

class EloquentProgramRepository implements ProgramRepositoryInterface
{
    public function findById(string $id)
    {
        return Program::find($id);
    }

    public function findAll(): array
    {
        return Program::all()->toArray();
    }

    public function save($program): void
    {
        $program->save();
    }

    public function delete(string $id): void
    {
        Program::destroy($id);
    }
}
