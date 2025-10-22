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
            throw new \DomainException('Project not found');
        }

        // Deletion guards: Validate all business rules before deletion
        $this->validateProjectCanBeDeleted($project);

        $this->projectRepository->delete($projectId);
    }

    private function validateProjectCanBeDeleted($project): void
    {
        // Deletion guard: Cannot delete projects with outcomes
        if ($project->getOutcomeCount() > 0) {
            throw new \DomainException('Cannot delete project with existing outcomes. Remove all outcomes first.');
        }

        // Deletion guard: Cannot delete active projects
        if ($project->getStatus()->getValue() === 'active') {
            throw new \DomainException('Cannot delete active project. Change project status first.');
        }

        // Deletion guard: Cannot delete completed projects
        if ($project->getStatus()->getValue() === 'completed') {
            throw new \DomainException('Cannot delete completed project. Completed projects should be archived instead.');
        }

        // Deletion guard: Projects with participants require participant removal first
        if ($project->getParticipantCount() > 0) {
            throw new \DomainException('Cannot delete project with assigned participants. Remove all team members first.');
        }

        // Deletion guard: Only planning or cancelled projects can be deleted
        $allowedStatuses = ['planning', 'cancelled', 'on_hold'];
        if (!in_array($project->getStatus()->getValue(), $allowedStatuses)) {
            throw new \DomainException('Only projects in planning, cancelled, or on-hold status can be deleted.');
        }
    }
}
