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
        $model = ProjectModel::with(['participants', 'outcomes'])->find($id);
        
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
        // For new projects, don't try to find by ID if it's a temporary UUID
        $model = null;
        if ($project->getId() && is_numeric($project->getId())) {
            $model = ProjectModel::find($project->getId());
        }
        
        if (!$model) {
            $model = new ProjectModel();
        }
        
        $model->fill([
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
            'participants' => json_encode($project->getParticipants()),
            'outcomes' => json_encode($project->getOutcomes()),
            'technical_requirements' => json_encode($project->getTechnicalRequirements()),
        ]);

        $model->save();
        
        // Update the project entity with the actual database ID
        // Note: This is a workaround since domain entities should be immutable
        // In a proper implementation, you'd return the new ID from this method
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
        // Map participants
        $participants = $model->participants ? $model->participants->map(function($participantModel) {
            return new \App\Domain\Entities\Participant(
                id: (string) $participantModel->id,
                fullName: $participantModel->full_name,
                email: $participantModel->email,
                affiliation: new \App\Domain\ValueObjects\ParticipantAffiliation($participantModel->affiliation),
                institution: $participantModel->institution,
                specialization: $participantModel->specialization ? new \App\Domain\ValueObjects\ParticipantSpecialization($participantModel->specialization) : null,
                crossSkillTrained: (bool) $participantModel->cross_skill_trained,
                projectId: (string) $participantModel->project_id
            );
        })->toArray() : [];

        // Map outcomes
        $outcomes = $model->outcomes ? $model->outcomes->map(function($outcomeModel) {
            return new \App\Domain\Entities\Outcome(
                id: (string) $outcomeModel->id,
                title: $outcomeModel->title,
                description: $outcomeModel->description,
                projectId: (string) $outcomeModel->project_id,
                outcomeType: new \App\Domain\ValueObjects\OutcomeType($outcomeModel->outcome_type),
                qualityCertification: $outcomeModel->quality_certification ?? '',
                dateAchieved: \Carbon\Carbon::parse($outcomeModel->date_achieved),
                commercializationStatus: new \App\Domain\ValueObjects\CommercializationStatus($outcomeModel->commercialization_status ?? ''),
                impact: $outcomeModel->impact ?? '',
                artifactLink: $outcomeModel->artifact_link ?? ''
            );
        })->toArray() : [];

        return new Project(
            id: (string) $model->id,
            programId: (string) $model->program_id,
            facilityId: (string) $model->facility_id,
            title: $model->title,
            natureOfProject: $model->nature_of_project,
            description: $model->description,
            innovationFocus: new \App\Domain\ValueObjects\InnovationFocus($model->innovation_focus),
            prototypeStage: new \App\Domain\ValueObjects\PrototypeStage($model->prototype_stage),
            testingRequirements: $model->testing_requirements,
            commercializationPlan: $model->commercialization_plan,
            status: new \App\Domain\ValueObjects\ProjectStatus($model->status ?? 'planning'),
            participants: $participants,
            outcomes: $outcomes,
            technicalRequirements: json_decode($model->technical_requirements ?? '[]', true)
        );
    }
}
