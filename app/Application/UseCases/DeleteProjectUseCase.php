<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\ProjectRepositoryInterface;

class DeleteProjectUseCase
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

        // Business rule validations before deletion
        $this->validateProjectCanBeDeleted($project);

        $this->projectRepository->delete($projectId);
    }

    private function validateProjectCanBeDeleted($project): void
    {
        // Business rule: Cannot delete projects with outcomes
        if ($project->getOutcomeCount() > 0) {
            throw new \DomainException('Cannot delete project with existing outcomes. Remove all outcomes first.');
        }

        // Business rule: Cannot delete active projects
        if ($project->getStatus()->getValue() === 'active') {
            throw new \DomainException('Cannot delete active project. Change project status first.');
        }

        // Business rule: Cannot delete completed projects
        if ($project->getStatus()->getValue() === 'completed') {
            throw new \DomainException('Cannot delete completed project. Completed projects should be archived instead.');
        }

        // Business rule: Projects with participants should be handled carefully
        if ($project->getParticipantCount() > 0) {
            // You might want to warn but allow deletion, or require participant removal first
            // For now, we'll allow deletion but could add a warning in the UI
        }
    }
}
