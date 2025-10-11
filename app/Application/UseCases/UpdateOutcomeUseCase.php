<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\OutcomeRepositoryInterface;
use App\Application\DTOs\UpdateOutcomeDTO;

class UpdateOutcomeUseCase
{
    private OutcomeRepositoryInterface $outcomeRepository;

    public function __construct(OutcomeRepositoryInterface $outcomeRepository)
    {
        $this->outcomeRepository = $outcomeRepository;
    }

    public function execute(string $outcomeId, UpdateOutcomeDTO $dto): void
    {
        $outcome = $this->outcomeRepository->findById($outcomeId);
        
        if (!$outcome) {
            throw new \Exception('Outcome not found');
        }

        $outcome->update($dto->toArray());
        $this->outcomeRepository->save($outcome);
    }
}
