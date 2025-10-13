<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\OutcomeRepositoryInterface;
use App\Domain\Repositories\ProjectRepositoryInterface;
use App\Application\DTOs\CreateOutcomeDTO;
use App\Domain\Entities\Outcome;
use App\Domain\ValueObjects\OutcomeType;
use App\Domain\ValueObjects\CommercializationStatus;
use Illuminate\Support\Str;

class CreateOutcomeUseCase
{
    private OutcomeRepositoryInterface $outcomeRepository;
    private ProjectRepositoryInterface $projectRepository;

    public function __construct(
        OutcomeRepositoryInterface $outcomeRepository,
        ProjectRepositoryInterface $projectRepository
    ) {
        $this->outcomeRepository = $outcomeRepository;
        $this->projectRepository = $projectRepository;
    }

    public function execute(CreateOutcomeDTO $dto): string
    {
        // Business rule: Project must exist
        $project = $this->projectRepository->findById($dto->projectId);
        if (!$project) {
            throw new \DomainException('Project not found');
        }

        $outcome = new Outcome(
            id: Str::uuid()->toString(),
            projectId: $dto->projectId,
            title: $dto->title,
            description: $dto->description,
            outcomeType: new OutcomeType($dto->outcomeType),
            qualityCertification: $dto->qualityCertification,
            dateAchieved: $dto->dateAchieved,
            commercializationStatus: new CommercializationStatus($dto->commercializationStatus),
            impact: $dto->impact,
            artifactLink: $dto->artifactLink
        );

        $this->outcomeRepository->save($outcome);
        
        return $outcome->getId();
    }
}
