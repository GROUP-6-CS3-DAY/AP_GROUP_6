<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\ProjectRepositoryInterface;
use App\Domain\ValueObjects\ProjectStatus;

class CompleteProjectUseCase
{
    private ProjectRepositoryInterface $projectRepository;

    public function __construct(ProjectRepositoryInterface $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    public function execute(string $projectId): void
    {
        $project = $this->projectRepository->findById($projectId);
        
        if (!$project) {
            throw new \Exception('Project not found');
        }

        // Business rule: Completed projects must have at least one outcome
        if (!$project->canBeCompleted()) {
            throw new \DomainException('Completed projects must have at least one documented outcome');
        }

        $project->update(['status' => 'completed']);
        $this->projectRepository->save($project);
    }
}
