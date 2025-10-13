<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\OutcomeRepositoryInterface;
use App\Domain\Repositories\ProjectRepositoryInterface;
use App\Application\DTOs\UpdateOutcomeDTO;

class UpdateOutcomeUseCase
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

    public function execute(string $outcomeId, UpdateOutcomeDTO $dto): void
    {
        $outcome = $this->outcomeRepository->findById($outcomeId);
        
        if (!$outcome) {
            throw new \Exception('Outcome not found');
        }

        // Business rule: Project must exist
        $project = $this->projectRepository->findById($dto->projectId);
        if (!$project) {
            throw new \DomainException('Project not found');
        }

        $outcome->update($dto->toArray());
        $this->outcomeRepository->save($outcome);
    }
}
