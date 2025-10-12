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
            throw new \Exception('Program not found');
        }

        // Business rule: Cannot delete programs with associated projects
        $program->validateDeletion();

        $this->programRepository->delete($programId);
    }
}
