<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\ProgramRepositoryInterface;
use App\Application\DTOs\CreateProgramDTO;
use App\Domain\Entities\Program;
use App\Domain\ValueObjects\ProgramPhase;
use Illuminate\Support\Str;

class CreateProgramUseCase
{
    private ProgramRepositoryInterface $programRepository;

    public function __construct(ProgramRepositoryInterface $programRepository)
    {
        $this->programRepository = $programRepository;
    }

    public function execute(CreateProgramDTO $dto): string
    {
        $program = new Program(
            id: Str::uuid()->toString(),
            name: $dto->name,
            description: $dto->description,
            nationalAlignment: $dto->nationalAlignment,
            focusAreas: $dto->focusAreas,
            phases: array_map(fn($phase) => new ProgramPhase($phase), $dto->phases)
        );

        $this->programRepository->save($program);
        
        return $program->getId();
    }
}
