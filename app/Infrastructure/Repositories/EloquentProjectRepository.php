<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Project;
use App\Domain\Repositories\ProjectRepositoryInterface;
use App\Domain\ValueObjects\InnovationFocus;
use App\Domain\ValueObjects\PrototypeStage;
use App\Domain\ValueObjects\ProjectStatus;
use App\Models\Project as ProjectModel;
use App\Domain\Entities\Participant;
use App\Domain\Entities\Outcome;
use App\Domain\ValueObjects\OutcomeType;
use App\Domain\ValueObjects\CommercializationStatus;
use App\Domain\ValueObjects\ParticipantAffiliation;
use App\Domain\ValueObjects\ParticipantSpecialization;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Participant as ParticipantModel;
use App\Models\Outcome as OutcomeModel;

class EloquentProjectRepository implements ProjectRepositoryInterface
{
    public function findById(string $id): ?Project
    {
        logger()->info("EloquentProjectRepository: Finding project by ID: {$id}");
        
        // Let's try without eager loading first to see if that's the issue
        $model = ProjectModel::find($id);
        
        if (!$model) {
            \Log::warning("EloquentProjectRepository: Project not found with ID: {$id}");
            return null;
        }

        logger()->info("EloquentProjectRepository: Project found", [
            'project_id' => $model->id,
            'title' => $model->title
        ]);

        // Manual check: Let's see what's actually in the database
        $participantCount = ParticipantModel::where('project_id', $id)->count();
        $outcomeCount = OutcomeModel::where('project_id', $id)->count();
        
        logger()->info("EloquentProjectRepository: Manual database count check", [
            'project_id' => $id,
            'participants_in_db' => $participantCount,
            'outcomes_in_db' => $outcomeCount
        ]);

        return $this->mapToEntity($model);
    }

    public function findAll(): array
    {
        return ProjectModel::with(['program', 'facility', 'participants', 'outcomes'])
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
        logger()->info("EloquentProjectRepository: Mapping project to entity", [
            'project_id' => $model->id,
            'participants_relation_loaded' => $model->relationLoaded('participants'),
            'outcomes_relation_loaded' => $model->relationLoaded('outcomes')
        ]);

        $participants = $this->mapParticipants($model);
        $outcomes = $this->mapOutcomes($model);

        logger()->info("EloquentProjectRepository: Mapped entities count", [
            'project_id' => $model->id,
            'mapped_participants_count' => count($participants),
            'mapped_outcomes_count' => count($outcomes)
        ]);

        return new Project(
            id: (string) $model->id,
            programId: (string) $model->program_id,
            facilityId: (string) $model->facility_id,
            title: $model->title,
            natureOfProject: $model->nature_of_project,
            description: $model->description,
            innovationFocus: new InnovationFocus($model->innovation_focus),
            prototypeStage: new PrototypeStage($model->prototype_stage),
            testingRequirements: $model->testing_requirements,
            commercializationPlan: $model->commercialization_plan,
            status: new ProjectStatus($model->status ?? 'planning'),
            participants: $participants,
            outcomes: $outcomes,
            technicalRequirements: json_decode($model->technical_requirements ?? '[]', true)
        );
    }

    private function mapParticipants(ProjectModel $model): array
    {
        logger()->info("EloquentProjectRepository: Manual participant fetching for project {$model->id}");
        
        // Manual query instead of using relationships
        $participantModels = ParticipantModel::where('project_id', $model->id)->get();
        
        logger()->info("EloquentProjectRepository: Manual participant query result", [
            'project_id' => $model->id,
            'participants_found' => $participantModels->count(),
            'participant_ids' => $participantModels->pluck('id')->toArray()
        ]);

        $participants = [];
        foreach ($participantModels as $participantModel) {
            logger()->debug("EloquentProjectRepository: Processing participant manually", [
                'project_id' => $model->id,
                'participant_id' => $participantModel->id,
                'participant_name' => $participantModel->full_name,
                'participant_project_id' => $participantModel->project_id
            ]);

            try {
                $participants[] = new Participant(
                    id: (string) $participantModel->id,
                    fullName: $participantModel->full_name,
                    email: $participantModel->email,
                    affiliation: new ParticipantAffiliation($participantModel->affiliation),
                    institution: $participantModel->institution,
                    specialization: $participantModel->specialization ? 
                        new ParticipantSpecialization($participantModel->specialization) : null,
                    crossSkillTrained: (bool) $participantModel->cross_skill_trained,
                    projectId: (string) $participantModel->project_id
                );

                logger()->debug("EloquentProjectRepository: Successfully mapped participant {$participantModel->id}");
            } catch (\Exception $e) {
                \Log::error("EloquentProjectRepository: Error mapping participant", [
                    'project_id' => $model->id,
                    'participant_id' => $participantModel->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        logger()->info("EloquentProjectRepository: Finished manual participant mapping", [
            'project_id' => $model->id,
            'total_mapped' => count($participants)
        ]);

        return $participants;
    }

    private function mapOutcomes(ProjectModel $model): array
    {
        logger()->info("EloquentProjectRepository: Manual outcome fetching for project {$model->id}");
        
        // Manual query instead of using relationships
        $outcomeModels = OutcomeModel::where('project_id', $model->id)->get();
        
        logger()->info("EloquentProjectRepository: Manual outcome query result", [
            'project_id' => $model->id,
            'outcomes_found' => $outcomeModels->count(),
            'outcome_ids' => $outcomeModels->pluck('id')->toArray()
        ]);

        $outcomes = [];
        foreach ($outcomeModels as $outcomeModel) {
            logger()->debug("EloquentProjectRepository: Processing outcome manually", [
                'project_id' => $model->id,
                'outcome_id' => $outcomeModel->id,
                'outcome_title' => $outcomeModel->title,
                'outcome_project_id' => $outcomeModel->project_id
            ]);

            try {
                $outcomes[] = new Outcome(
                    id: (string) $outcomeModel->id,
                    title: $outcomeModel->title,
                    description: $outcomeModel->description,
                    projectId: (string) $outcomeModel->project_id,
                    outcomeType: new OutcomeType($outcomeModel->outcome_type),
                    qualityCertification: $outcomeModel->quality_certification ?? '',
                    dateAchieved: $outcomeModel->date_achieved instanceof Carbon ? 
                        $outcomeModel->date_achieved : Carbon::parse($outcomeModel->date_achieved),
                    commercializationStatus: new CommercializationStatus(
                        $outcomeModel->commercialization_status ?? ''
                    ),
                    impact: $outcomeModel->impact ?? '',
                    artifactLink: $outcomeModel->artifact_link ?? ''
                );

                logger()->debug("EloquentProjectRepository: Successfully mapped outcome {$outcomeModel->id}");
            } catch (\Exception $e) {
                \Log::error("EloquentProjectRepository: Error mapping outcome", [
                    'project_id' => $model->id,
                    'outcome_id' => $outcomeModel->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        logger()->info("EloquentProjectRepository: Finished manual outcome mapping", [
            'project_id' => $model->id,
            'total_mapped' => count($outcomes)
        ]);

        return $outcomes;
    }

    // Add a debug method to check database structure
    public function debugProjectRelationships(string $projectId): array
    {
        $debug = [];
        
        // Check if project exists
        $project = ProjectModel::find($projectId);
        $debug['project_exists'] = $project !== null;
        $debug['project_data'] = $project ? $project->toArray() : null;
        
        // Check participants table directly
        $participantsQuery = \DB::table('participants')->where('project_id', $projectId);
        $debug['participants_raw_count'] = $participantsQuery->count();
        $debug['participants_raw_data'] = $participantsQuery->get()->toArray();
        
        // Check outcomes table directly
        $outcomesQuery = \DB::table('outcomes')->where('project_id', $projectId);
        $debug['outcomes_raw_count'] = $outcomesQuery->count();
        $debug['outcomes_raw_data'] = $outcomesQuery->get()->toArray();
        
        // Check if tables exist
        $debug['participants_table_exists'] = \Schema::hasTable('participants');
        $debug['outcomes_table_exists'] = \Schema::hasTable('outcomes');
        
        // Check table structure
        if (\Schema::hasTable('participants')) {
            $debug['participants_columns'] = \Schema::getColumnListing('participants');
        }
        
        if (\Schema::hasTable('outcomes')) {
            $debug['outcomes_columns'] = \Schema::getColumnListing('outcomes');
        }
        
        logger()->info("Database debug info for project {$projectId}", $debug);
        
        return $debug;
    }
}
