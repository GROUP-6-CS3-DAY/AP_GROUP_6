<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\OutcomeRepositoryInterface;
use App\Application\Exceptions\OutcomeNotFoundException;

class DeleteOutcomeUseCase
{
    public function __construct(
        private OutcomeRepositoryInterface $outcomeRepository
    ) {}

    public function execute(string $outcomeId): void
    {
        $outcome = $this->outcomeRepository->findById($outcomeId);
        
        if (!$outcome) {
            throw new OutcomeNotFoundException("Outcome with ID {$outcomeId} not found");
        }

        // Business rule: Cannot delete outcomes that are commercialized
        if ($outcome->getCommercializationStatus()->getValue() === 'commercialized') {
            throw new \DomainException('Cannot delete commercialized outcomes');
        }

        $this->outcomeRepository->delete($outcomeId);
    }
}
