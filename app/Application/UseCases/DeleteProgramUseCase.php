<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\ProgramRepositoryInterface;

class DeleteProgramUseCase
{
    private ProgramRepositoryInterface $programRepository;

    public function __construct(ProgramRepositoryInterface $programRepository)
    {
        $this->programRepository = $programRepository;
    }

    public function execute(string $programId): void
    {
        $program = $this->programRepository->findById($programId);
        
        if (!$program) {
            throw new \DomainException('Program not found');
        }

        // Deletion guards: Validate all business rules before deletion
        $this->validateProgramCanBeDeleted($program);

        $this->programRepository->delete($programId);
    }

    private function validateProgramCanBeDeleted($program): void
    {
        // Deletion guard: Cannot delete programs with associated projects
        if (!$program->canBeDeleted()) {
            throw new \DomainException('Cannot delete program with active projects. Archive or reassign projects first.');
        }

        // Additional deletion guard: Check if program is active
        if ($program->isActive()) {
            throw new \DomainException('Cannot delete active programs. Deactivate the program first.');
        }

        // Deletion guard: Programs that can accept projects shouldn't be deleted
        if ($program->canAcceptProjects()) {
            throw new \DomainException('Cannot delete program that is configured to accept projects. Remove focus areas or national alignment first.');
        }

        // Deletion guard: Check project count explicitly
        if ($program->getProjectCount() > 0) {
            throw new \DomainException("Cannot delete program with {$program->getProjectCount()} associated project(s). Remove or reassign all projects first.");
        }
    }
}
