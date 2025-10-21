<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\ProgramRepositoryInterface;
use App\Domain\Entities\Program;

class GetProgramWithProjectsUseCase
{
    public function __construct(
        private ProgramRepositoryInterface $programRepository
    ) {}

    public function execute(string $programId): ?Program
    {
        return $this->programRepository->findById($programId);
    }
}
