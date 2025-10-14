<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\OutcomeRepositoryInterface;
use App\Domain\Repositories\ProjectRepositoryInterface;
use App\Application\DTOs\UpdateOutcomeDTO;

class UpdateOutcomeUseCase
{
    public function __construct(
        private OutcomeRepositoryInterface $outcomeRepository,
        private ProjectRepositoryInterface $projectRepository
    ) {}

    public function execute(string $id, UpdateOutcomeDTO $dto): void
    {
        // Business rule: Outcome must exist
        $outcome = $this->outcomeRepository->findById($id);
        if (!$outcome) {
            throw new \DomainException('Outcome not found');
        }

        // Business rule: Project must exist
        $project = $this->projectRepository->findById($dto->projectId);
        if (!$project) {
            throw new \DomainException('Project not found');
        }

        // Update outcome with new data
        $updatedOutcome = new \App\Domain\Entities\Outcome(
            id: $id,
            title: $dto->title,
            description: $dto->description,
            projectId: $dto->projectId,
            outcomeType: $dto->outcomeType,
            qualityCertification: $dto->qualityCertification ?? '',
            dateAchieved: \Carbon\Carbon::parse($dto->dateAchieved),
            commercializationStatus: $dto->commercializationStatus ?? '',
            impact: $dto->impact ?? '',
            artifactLink: $dto->artifactLink ?? ''
        );

        $this->outcomeRepository->save($updatedOutcome);
    }
}
