<?php

namespace App\Application\UseCases\Outcomes;

use App\Domain\Repositories\OutcomeRepositoryInterface;

class GetOutcomesWithFiltersUseCase
{
    public function __construct(
        private OutcomeRepositoryInterface $outcomeRepository
    ) {}

    public function execute(array $filters, int $perPage = 15): array
    {
        return $this->outcomeRepository->findWithFilters($filters, $perPage);
    }
}
