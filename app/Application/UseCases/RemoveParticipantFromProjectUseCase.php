<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\ProjectRepositoryInterface;

class RemoveParticipantFromProjectUseCase
{
    private ProjectRepositoryInterface $projectRepository;

    public function __construct(ProjectRepositoryInterface $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    public function execute(string $projectId, string $participantId): void
    {
        $project = $this->projectRepository->findById($projectId);
        
        if (!$project) {
            throw new \Exception('Project not found');
        }

        // Business rule: Project must have at least one team member
        $project->removeParticipant($participantId);
        $this->projectRepository->save($project);
    }
}
