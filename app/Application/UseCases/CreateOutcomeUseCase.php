<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\OutcomeRepositoryInterface;
use App\Application\DTOs\CreateOutcomeDTO;
use App\Domain\Entities\Outcome;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CreateOutcomeUseCase
{
    private OutcomeRepositoryInterface $outcomeRepository;

    public function __construct(OutcomeRepositoryInterface $outcomeRepository)
    {
        $this->outcomeRepository = $outcomeRepository;
    }

    public function execute(CreateOutcomeDTO $dto): string
    {
        $outcome = new Outcome(
            id: Str::uuid()->toString(),
            title: $dto->title,
            description: $dto->description,
            projectId: $dto->projectId,
            outcomeType: $dto->outcomeType,
            qualityCertification: $dto->qualityCertification,
            dateAchieved: Carbon::parse($dto->dateAchieved),
            commercializationStatus: $dto->commercializationStatus,
            impact: $dto->impact,
            artifactLink: $dto->artifactLink
        );

        $this->outcomeRepository->save($outcome);
        
        return $outcome->getId();
    }
}
