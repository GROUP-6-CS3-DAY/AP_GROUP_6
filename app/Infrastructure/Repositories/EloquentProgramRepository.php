<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Program;
use App\Domain\Repositories\ProgramRepositoryInterface;
use App\Domain\ValueObjects\ProgramPhase;
use App\Models\Program as ProgramModel;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentProgramRepository implements ProgramRepositoryInterface
{
    public function findById(string $id): ?Program
    {
        $model = ProgramModel::with(['projects.participants', 'projects.outcomes'])->find($id);
        
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

    public function findWithFilters(array $filters, int $perPage = 15): array
    {
        $query = ProgramModel::query();

        // Search functionality
        if (!empty($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('description', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('national_alignment', 'like', '%' . $filters['search'] . '%');
            });
        }

        // Focus areas filter
        if (!empty($filters['focus_areas'])) {
            $query->where('focus_areas', 'like', '%' . $filters['focus_areas'] . '%');
        }

        // Phases filter
        if (!empty($filters['phases'])) {
            $query->where('phases', 'like', '%' . $filters['phases'] . '%');
        }

        $paginatedResults = $query->orderBy('name')->paginate($perPage);
        
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

    private function mapToEntity(ProgramModel $model): Program
    {
        // Parse focus areas and phases from string format
        $focusAreas = !empty($model->focus_areas) ? array_map('trim', explode(',', $model->focus_areas)) : [];
        $phases = !empty($model->phases) ? array_map('trim', explode(',', $model->phases)) : [];

        // Map projects with their participants and outcomes
        $projects = $model->projects ? $model->projects->map(function($projectModel) {
            return $this->mapProjectToEntity($projectModel);
        })->toArray() : [];

        return new Program(
            id: (string) $model->getKey(),
            name: $model->name,
            description: $model->description,
            nationalAlignment: $model->national_alignment,
            focusAreas: $focusAreas,
            phases: $phases,
            projects: $projects
        );
    }

    private function mapProjectToEntity($projectModel): \App\Domain\Entities\Project
    {
        // Map participants
        $participants = $projectModel->participants ? $projectModel->participants->map(function($participantModel) {
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
        $outcomes = $projectModel->outcomes ? $projectModel->outcomes->map(function($outcomeModel) {
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

        return new \App\Domain\Entities\Project(
            id: (string) $projectModel->id,
            programId: (string) $projectModel->program_id,
            facilityId: (string) $projectModel->facility_id,
            title: $projectModel->title,
            natureOfProject: $projectModel->nature_of_project,
            description: $projectModel->description,
            innovationFocus: new \App\Domain\ValueObjects\InnovationFocus($projectModel->innovation_focus),
            prototypeStage: new \App\Domain\ValueObjects\PrototypeStage($projectModel->prototype_stage),
            testingRequirements: $projectModel->testing_requirements,
            commercializationPlan: $projectModel->commercialization_plan,
            status: new \App\Domain\ValueObjects\ProjectStatus($projectModel->status ?? 'planning'),
            participants: $participants,
            outcomes: $outcomes,
            technicalRequirements: json_decode($projectModel->technical_requirements ?? '[]', true)
        );
    }
}
