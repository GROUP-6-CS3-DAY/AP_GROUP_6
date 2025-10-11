<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Outcome;
use App\Domain\Repositories\OutcomeRepositoryInterface;
use App\Models\Outcome as OutcomeModel;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentOutcomeRepository implements OutcomeRepositoryInterface
{
    public function findById(string $id): ?Outcome
    {
        $model = OutcomeModel::with(['project'])->find($id);
        
        if (!$model) {
            return null;
        }

        return $this->mapToEntity($model);
    }

    public function findWithFilters(array $filters, int $perPage = 15): array
    {
        $query = OutcomeModel::with(['project']);

        if (!empty($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('title', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('description', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('outcome_type', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('commercialization_status', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('impact', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (!empty($filters['outcome_type'])) {
            $query->where('outcome_type', $filters['outcome_type']);
        }

        if (!empty($filters['commercialization_status'])) {
            $query->where('commercialization_status', $filters['commercialization_status']);
        }

        if (!empty($filters['project_id'])) {
            $query->where('project_id', $filters['project_id']);
        }

        $paginatedResults = $query->orderByDesc('date_achieved')->paginate($perPage);
        
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

    public function save(Outcome $outcome): void
    {
        $model = OutcomeModel::find($outcome->getId()) ?? new OutcomeModel();
        
        $model->fill([
            'id' => $outcome->getId(),
            'project_id' => $outcome->getProjectId(),
            'title' => $outcome->getTitle(),
            'description' => $outcome->getDescription(),
            'outcome_type' => $outcome->getOutcomeType(),
            'quality_certification' => $outcome->getQualityCertification(),
            'date_achieved' => $outcome->getDateAchieved(),
            'commercialization_status' => $outcome->getCommercializationStatus(),
            'impact' => $outcome->getImpact(),
            'artifact_link' => $outcome->getArtifactLink(),
        ]);

        $model->save();
    }

    public function delete(string $id): void
    {
        OutcomeModel::destroy($id);
    }

    public function findByProjectId(string $projectId): array
    {
        return OutcomeModel::where('project_id', $projectId)
            ->get()
            ->map(fn($model) => $this->mapToEntity($model))
            ->toArray();
    }

    private function mapToEntity(OutcomeModel $model): Outcome
    {
        return new Outcome(
            id: (string) $model->getKey(),
            title: $model->title,
            description: $model->description,
            projectId: $model->project_id,
            outcomeType: $model->outcome_type,
            qualityCertification: $model->quality_certification ?? '',
            dateAchieved: Carbon::parse($model->date_achieved),
            commercializationStatus: $model->commercialization_status ?? '',
            impact: $model->impact ?? '',
            artifactLink: $model->artifact_link ?? ''
        );
    }
}
