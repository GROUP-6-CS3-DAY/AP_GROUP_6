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
        $programId = 'program-123';
        
        $mockProgram = $this->createMock(Program::class);
        $mockProgram->method('canBeDeleted')->willReturn(true);
        $mockProgram->method('isActive')->willReturn(false); // Not active
        $mockProgram->method('canAcceptProjects')->willReturn(false); // Cannot accept projects
        $mockProgram->method('getProjectCount')->willReturn(0);

        $this->mockRepository
            ->expects($this->once())
            ->method('findById')
            ->with($programId)
            ->willReturn($mockProgram);

        $this->mockRepository
            ->expects($this->once())
            ->method('delete')
            ->with($programId);

        $this->useCase->execute($programId);
    }

    public function test_cannot_delete_program_with_associated_projects()
    {
        $programId = 'program-123';
        
        $mockProgram = $this->createMock(Program::class);
        $mockProgram->method('canBeDeleted')->willReturn(false);
        $mockProgram->method('getProjectCount')->willReturn(2);

        $this->mockRepository
            ->expects($this->once())
            ->method('findById')
            ->with($programId)
            ->willReturn($mockProgram);

        $this->mockRepository
            ->expects($this->never())
            ->method('delete');

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Cannot delete program with active projects');

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
