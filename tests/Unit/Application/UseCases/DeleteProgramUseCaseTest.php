<?php

namespace Tests\Unit\Application\UseCases;

use App\Application\UseCases\DeleteProgramUseCase;
use App\Domain\Repositories\ProgramRepositoryInterface;
use App\Domain\Entities\Program;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class DeleteProgramUseCaseTest extends TestCase
{
    private ProgramRepositoryInterface|MockObject $mockRepository;
    private DeleteProgramUseCase $useCase;

    protected function setUp(): void
    {
        $this->mockRepository = $this->createMock(ProgramRepositoryInterface::class);
        $this->useCase = new DeleteProgramUseCase($this->mockRepository);
    }

    public function test_can_delete_program_without_projects()
    {
        $programId = 'prog-123';
        
        $program = new Program(
            id: $programId,
            name: 'Test Program',
            description: 'Test description here',
            nationalAlignment: 'NDPIII',
            focusAreas: ['AI'],
            phases: ['planning'],
            projects: [] // No projects
        );

        $this->mockRepository
            ->expects($this->once())
            ->method('findById')
            ->with($programId)
            ->willReturn($program);

        $this->mockRepository
            ->expects($this->once())
            ->method('delete')
            ->with($programId);

        $this->useCase->execute($programId);
    }

    public function test_cannot_delete_program_with_associated_projects()
    {
        $programId = 'prog-123';
        
        $program = new Program(
            id: $programId,
            name: 'Test Program',
            description: 'Test description here',
            nationalAlignment: 'NDPIII',
            focusAreas: ['AI'],
            phases: ['planning'],
            projects: ['project-1', 'project-2'] // Has projects
        );

        $this->mockRepository
            ->expects($this->once())
            ->method('findById')
            ->with($programId)
            ->willReturn($program);

        $this->mockRepository
            ->expects($this->never())
            ->method('delete');

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Program has Projects; archive or reassign before delete');

        $this->useCase->execute($programId);
    }

    public function test_throws_exception_when_program_not_found()
    {
        $programId = 'non-existent-123';

        $this->mockRepository
            ->expects($this->once())
            ->method('findById')
            ->with($programId)
            ->willReturn(null);

        $this->mockRepository
            ->expects($this->never())
            ->method('delete');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Program not found');

        $this->useCase->execute($programId);
    }
}
