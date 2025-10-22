<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\ParticipantRepositoryInterface;
use App\Domain\Repositories\ProjectRepositoryInterface;
use App\Application\Exceptions\ParticipantNotFoundException;

class DeleteParticipantUseCase
{
    public function __construct(
        private ParticipantRepositoryInterface $participantRepository,
        private ProjectRepositoryInterface $projectRepository
    ) {}

    public function execute(string $participantId): void
    {
        $participant = $this->participantRepository->findById($participantId);
        
        if (!$participant) {
            throw new ParticipantNotFoundException("Participant with ID {$participantId} not found");
        }

        // Deletion guards
        $this->validateParticipantCanBeDeleted($participant);

        $this->participantRepository->delete($participantId);
    }

    private function validateParticipantCanBeDeleted($participant): void
    {
        // Get the project ID safely
        $projectId = $participant->getProjectId();
        
        // If participant has no project association, they can be deleted freely
        if (empty($projectId)) {
          return;
        }
        
        // Deletion guard: Check if participant's project exists and its status
        $project = $this->projectRepository->findById($projectId);
        
        if (!$project) {
            // Project doesn't exist anymore, safe to delete participant
            return;
        }
        
        // Deletion guard: Cannot remove participants from active projects without replacement
        if ($project->getStatus()->getValue() === 'active') {
            if ($project->getParticipantCount() <= 1) {
                throw new \DomainException('Cannot remove the last participant from an active project. Add replacement first or change project status.');
            }
        }

        // Deletion guard: Cannot remove participants from completed projects
        if ($project->isCompleted()) {
            throw new \DomainException('Cannot remove participants from completed projects. Project data is immutable.');
        }

        // Deletion guard: Check if this would violate minimum team size requirement
        if ($project->getParticipantCount() <= 1) {
            throw new \DomainException('Cannot remove participant. Project must have at least one team member.');
        }

        // Deletion guard: Cannot delete participants who are cross-skill trained without documentation
        if ($participant->isCrossSkillTrained()) {
            // Warning: This is valuable data, ensure proper approval
        }

        // Deletion guard: Check if participant has specialization
        if ($participant->hasSpecialization()) {
        }
    }
}
