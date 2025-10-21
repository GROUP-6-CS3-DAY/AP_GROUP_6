<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\ProjectRepositoryInterface;
use App\Domain\Entities\Project;

class GetProjectWithDetailsUseCase
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository
    ) {}

    public function execute(string $projectId): ?Project
    {
        // Debug the project ID to make sure it's what we expect
        // logger()->debug("Loading project with ID: " . $projectId);
        
        // The repository is responsible for loading relationships
        $project = $this->projectRepository->findById($projectId);
        
        // Debug the returned project to verify data
        if ($project) {
            logger()->debug("Project loaded successfully: " . $project->getTitle());
            logger()->debug("Participant count: " . count($project->getParticipants()));
            logger()->debug("Outcome count: " . count($project->getOutcomes()));
        } else {
            logger()->debug("Project not found with ID: " . $projectId);
        }
        
        return $project;
    }
}
