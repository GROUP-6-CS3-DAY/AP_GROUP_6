<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Project;
use App\Domain\Repositories\ProjectRepositoryInterface;
use App\Domain\ValueObjects\InnovationFocus;
use App\Domain\ValueObjects\PrototypeStage;
use App\Domain\ValueObjects\ProjectStatus;
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
        $query = ProjectModel::with(['program', 'facility', 'participants', 'outcomes']);

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

    public function findProjectNamesByProgramId(string $programId, ?string $excludeProjectId = null): array
    {
        $query = ProjectModel::where('program_id', $programId);
        
        if ($excludeProjectId) {
            $query->where('id', '!=', $excludeProjectId);
        }
        
        return $query->pluck('title')->toArray();
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
            'status' => $project->getStatus()->getValue(),
            'technical_requirements' => json_encode($project->getTechnicalRequirements()),
        ]);

        $model->save();
        
        // Sync participants and outcomes if needed
        // This might require additional relationship handling
    }

    public function delete(string $id): void
    {
        // Find the project to check business rules before deletion
        $project = $this->findById($id);
        
        if ($project) {
            // Business rule: Cannot delete projects with outcomes
            if ($project->getOutcomeCount() > 0) {
                throw new \DomainException('Cannot delete project with existing outcomes. Remove outcomes first.');
            }
            
            // Business rule: Cannot delete active projects
            if ($project->getStatus()->getValue() === 'active') {
                throw new \DomainException('Cannot delete active project. Change status first.');
            }
        }
        
        ProjectModel::destroy($id);
    }

    public function findActiveProjectsByFacilityId(string $facilityId): array
    {
        return ProjectModel::where('facility_id', $facilityId)
            ->whereIn('status', ['active', 'planning']) // Consider active and planning as active
            ->get()
            ->map(function($model) {
                return [
                    'id' => (string) $model->getKey(),
                    'status' => $model->status ?? 'planning',
                    'equipment_ids' => $model->equipment_ids ? json_decode($model->equipment_ids, true) : [],
                    'technical_requirements' => $model->technical_requirements ? json_decode($model->technical_requirements, true) : []
                ];
            })
            ->toArray();
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
            status: new ProjectStatus($model->status ?? 'planning'),
            participants: $model->participants ? $model->participants->pluck('id')->toArray() : [],
            outcomes: $model->outcomes ? $model->outcomes->pluck('id')->toArray() : [],
            technicalRequirements: $model->technical_requirements ? json_decode($model->technical_requirements, true) : []
        );
    }
}
