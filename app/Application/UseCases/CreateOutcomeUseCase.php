<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\OutcomeRepositoryInterface;
use App\Domain\Repositories\ProjectRepositoryInterface;
use App\Application\DTOs\CreateOutcomeDTO;
use App\Domain\Entities\Outcome;
use App\Domain\ValueObjects\OutcomeType;
use App\Domain\ValueObjects\CommercializationStatus;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;

class CreateOutcomeUseCase
{
    public function __construct(
        private OutcomeRepositoryInterface $outcomeRepository,
        private ProjectRepositoryInterface $projectRepository
    ) {}

    public function execute(CreateOutcomeDTO $dto): string
    {
        // Business rule: Project must exist
        $project = $this->projectRepository->findById($dto->projectId);
        if (!$project) {
            throw new \DomainException('Project not found');
        }

        $outcome = new Outcome(
            id: Uuid::uuid4()->toString(),
            title: $dto->title,
            description: $dto->description,
            projectId: $dto->projectId,
            outcomeType: new OutcomeType($dto->outcomeType),
            qualityCertification: $dto->qualityCertification ?? '',
            dateAchieved: Carbon::parse($dto->dateAchieved),
            commercializationStatus: new CommercializationStatus($dto->commercializationStatus ?? ''),
            impact: $dto->impact ?? '',
            artifactLink: $dto->artifactLink ?? ''
        );

        $this->outcomeRepository->save($outcome);
        
        return $outcome->getId();
    }
}
