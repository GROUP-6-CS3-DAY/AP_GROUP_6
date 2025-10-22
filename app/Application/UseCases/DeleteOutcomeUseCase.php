<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\OutcomeRepositoryInterface;
use App\Domain\Repositories\ProjectRepositoryInterface;
use App\Application\Exceptions\OutcomeNotFoundException;

class DeleteOutcomeUseCase
{
    public function __construct(
        private OutcomeRepositoryInterface $outcomeRepository,
        private ProjectRepositoryInterface $projectRepository
    ) {}

    public function execute(string $outcomeId): void
    {
        $outcome = $this->outcomeRepository->findById($outcomeId);
        
        if (!$outcome) {
            throw new OutcomeNotFoundException("Outcome not found");
        }

        // Deletion guards
        $this->validateOutcomeCanBeDeleted($outcome);

        $this->outcomeRepository->delete($outcomeId);
    }

    private function validateOutcomeCanBeDeleted($outcome): void
    {
        // Deletion guard: Cannot delete outcomes that are commercialized
        if ($outcome->getCommercializationStatus()->getValue() === 'commercialized') {
            throw new \DomainException('Cannot delete outcome that has been commercialized. Contact administrator for archival.');
        }

        // Deletion guard: Cannot delete outcomes from completed projects
        $project = $this->projectRepository->findById($outcome->getProjectId());
        if ($project && $project->isCompleted()) {
            throw new \DomainException('Cannot delete outcomes from completed projects. Completed project data is immutable.');
        }

        // Deletion guard: Cannot delete high-impact outcomes without special approval
        if ($outcome->isHighImpact()) {
            throw new \DomainException('Cannot delete high-impact outcomes. These require special approval for removal.');
        }

        // Deletion guard: Check if outcome has quality certification
        if (!empty($outcome->getQualityCertification())) {
            throw new \DomainException('Cannot delete certified outcomes. Remove certification first or contact administrator.');
        }

        // Deletion guard: Check commercialization status
        $restrictedStatuses = ['in_market', 'licensed', 'patent_pending'];
        if (in_array($outcome->getCommercializationStatus()->getValue(), $restrictedStatuses)) {
            throw new \DomainException('Cannot delete outcome with active commercialization status: ' . $outcome->getCommercializationStatus()->getValue());
        }
    }
}
