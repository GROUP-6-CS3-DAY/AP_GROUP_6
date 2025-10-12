<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Program;
use App\Domain\Repositories\ProgramRepositoryInterface;
use App\Domain\ValueObjects\ProgramPhase;
use App\Models\Program as ProgramModel;

class EloquentProgramRepository implements ProgramRepositoryInterface
{
    public function findById(string $id): ?Program
    {
        $model = ProgramModel::with(['projects'])->find($id);
        
        if (!$model) {
            return null;
        }

        return $this->mapToEntity($model);
    }

    public function findAll(): array
    {
        return ProgramModel::all()
            ->map(fn($model) => $this->mapToEntity($model))
            ->toArray();
    }

    public function save(Program $program): void
    {
        $model = ProgramModel::find($program->getId()) ?? new ProgramModel();
        
        $model->fill([
            'id' => $program->getId(),
            'name' => $program->getName(),
            'description' => $program->getDescription(),
            'national_alignment' => $program->getNationalAlignment(),
            'focus_areas' => $program->getFocusAreasAsString(),
            'phases' => $program->getPhasesAsString(),
        ]);

        $model->save();
    }

    public function delete(string $id): void
    {
        ProgramModel::destroy($id);
    }

    public function findByName(string $name): ?Program
    {
        // Case-insensitive search for program name
        $model = ProgramModel::whereRaw('LOWER(name) = LOWER(?)', [$name])->first();
        
        if (!$model) {
            return null;
        }

        return $this->mapToEntity($model);
    }

    private function mapToEntity(ProgramModel $model): Program
    {
        // Parse focus areas and phases from string format
        $focusAreas = !empty($model->focus_areas) ? array_map('trim', explode(',', $model->focus_areas)) : [];
        $phases = !empty($model->phases) ? array_map('trim', explode(',', $model->phases)) : [];

        return new Program(
            id: (string) $model->getKey(),
            name: $model->name,
            description: $model->description,
            nationalAlignment: $model->national_alignment,
            focusAreas: $focusAreas,
            phases: $phases,
            projects: $model->projects ? $model->projects->toArray() : []
        );
    }
}
