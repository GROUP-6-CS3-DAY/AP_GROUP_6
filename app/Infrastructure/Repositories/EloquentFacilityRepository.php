<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\FacilityRepositoryInterface;
use App\Models\Facility;

class EloquentFacilityRepository implements FacilityRepositoryInterface
{
    public function findById(string $id)
    {
        return Facility::find($id);
    }

    public function findAll(): array
    {
        return Facility::all()->toArray();
    }

    public function save($facility): void
    {
        $facility->save();
    }

    public function delete(string $id): void
    {
        Facility::destroy($id);
    }
}
