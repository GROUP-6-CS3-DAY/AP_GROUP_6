<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\ProgramRepositoryInterface;
use App\Application\DTOs\UpdateProgramDTO;

class UpdateProgramUseCase
{
    private ProgramRepositoryInterface $programRepository;

    public function __construct(ProgramRepositoryInterface $programRepository)
    {
        $this->programRepository = $programRepository;
    }

    public function execute(string $programId, UpdateProgramDTO $dto): void
    {
        $program = $this->programRepository->findById($programId);
        
        if (!$program) {
            throw new \Exception('Program not found');
        }

        $program->update($dto->toArray());
        $this->programRepository->save($program);
    }
}
