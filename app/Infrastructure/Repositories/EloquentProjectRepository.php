<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Project;
use App\Domain\Repositories\ProjectRepositoryInterface;
use App\Domain\ValueObjects\InnovationFocus;
use App\Domain\ValueObjects\PrototypeStage;
use App\Models\Project as ProjectModel;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentProjectRepository implements ProjectRepositoryInterface
{
    public function findById(string $id): ?Project
    {
        $model = ProjectModel::with(['program', 'facility', 'participants', 'outcomes'])->find($id);
        
        if (!$model) {
            return null;
        }

        return $this->mapToEntity($model);
    }

    public function findAll(): array
    {
        return ProjectModel::with(['program', 'facility'])
            ->get()
            ->map(fn($model) => $this->mapToEntity($model))
            ->toArray();
    }

    public function findWithFilters(array $filters, int $perPage = 15): array
    {
        $query = ProjectModel::with(['program', 'facility']);

        if (!empty($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('title', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('description', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (!empty($filters['innovation_focus'])) {
            $query->where('innovation_focus', $filters['innovation_focus']);
        }

        if (!empty($filters['prototype_stage'])) {
            $query->where('prototype_stage', $filters['prototype_stage']);
        }

        if (!empty($filters['program_id'])) {
            $query->where('program_id', $filters['program_id']);
        }

        $paginatedResults = $query->paginate($perPage);
        
        // Transform the paginated results to domain entities
        $transformedItems = $paginatedResults->getCollection()->map(function($model) {
            return $this->mapToEntity($model);
        });

        // Create new paginator with transformed items
        $pagination = new LengthAwarePaginator(
            $transformedItems,
            $paginatedResults->total(),
            $paginatedResults->perPage(),
            $paginatedResults->currentPage(),
            [
                'path' => request()->url(),
                'pageName' => 'page',
            ]
        );

        return [
            'pagination' => $pagination,
            'data' => $transformedItems->toArray()
        ];
    }

    public function save(Project $project): void
    {
        $model = ProjectModel::find($project->getId()) ?? new ProjectModel();
        
        $model->fill([
            'id' => $project->getId(),
            'program_id' => $project->getProgramId(),
            'facility_id' => $project->getFacilityId(),
            'title' => $project->getTitle(),
            'nature_of_project' => $project->getNatureOfProject(),
            'description' => $project->getDescription(),
            'innovation_focus' => $project->getInnovationFocus()->getValue(),
            'prototype_stage' => $project->getPrototypeStage()->getValue(),
            'testing_requirements' => $project->getTestingRequirements(),
            'commercialization_plan' => $project->getCommercializationPlan(),
        ]);

        $model->save();
    }

    public function delete(string $id): void
    {
        ProjectModel::destroy($id);
    }

    private function mapToEntity(ProjectModel $model): Project
    {
        return new Project(
            id: (string) $model->getKey(),
            programId: $model->program_id,
            facilityId: $model->facility_id,
            title: $model->title,
            natureOfProject: $model->nature_of_project,
            description: $model->description,
            innovationFocus: new InnovationFocus($model->innovation_focus),
            prototypeStage: new PrototypeStage($model->prototype_stage),
            testingRequirements: $model->testing_requirements,
            commercializationPlan: $model->commercialization_plan,
            participants: $model->participants ? $model->participants->toArray() : [],
            outcomes: $model->outcomes ? $model->outcomes->toArray() : []
        );
    }
}
