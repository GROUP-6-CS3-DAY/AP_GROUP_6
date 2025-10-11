<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Outcome;
use App\Domain\Repositories\OutcomeRepositoryInterface;
use App\Models\Outcome as OutcomeModel;
use Carbon\Carbon;

class EloquentOutcomeRepository implements OutcomeRepositoryInterface
{
    public function findById(string $id): ?Outcome
    {
        $model = OutcomeModel::find($id);
        
        if (!$model) {
            return null;
        }

        return $this->mapToEntity($model);
    }

    public function save(Outcome $outcome): void
    {
        $model = OutcomeModel::find($outcome->getId()) ?? new OutcomeModel();
        
        $model->fill([
            'title' => $outcome->getTitle(),
            'description' => $outcome->getDescription(),
            'project_id' => $outcome->getProjectId(),
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

    public function findAll(): array
    {
        return OutcomeModel::all()->map(fn($model) => $this->mapToEntity($model))->toArray();
    }

    private function mapToEntity(OutcomeModel $model): Outcome
    {
        return new Outcome(
            id: (string) $model->getKey(),
            title: $model->title,
            description: $model->description,
            projectId: $model->project_id,
            outcomeType: $model->outcome_type,
            qualityCertification: $model->quality_certification,
            dateAchieved: Carbon::parse($model->date_achieved),
            commercializationStatus: $model->commercialization_status,
            impact: $model->impact,
            artifactLink: $model->artifact_link
        );
    }
}
