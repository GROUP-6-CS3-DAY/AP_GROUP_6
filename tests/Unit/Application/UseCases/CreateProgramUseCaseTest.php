<?php

namespace Tests\Unit\Application\UseCases;

use App\Application\UseCases\CreateProgramUseCase;
use App\Application\DTOs\CreateProgramDTO;
use App\Domain\Repositories\ProgramRepositoryInterface;
use App\Domain\Entities\Program;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class CreateProgramUseCaseTest extends TestCase
{
    private ProgramRepositoryInterface|MockObject $mockRepository;
    private CreateProgramUseCase $useCase;

    protected function setUp(): void
    {
        $this->mockRepository = $this->createMock(ProgramRepositoryInterface::class);
        $this->useCase = new CreateProgramUseCase($this->mockRepository);
    }

    public function test_can_create_program_with_valid_data()
    {
        $dto = new CreateProgramDTO(
            name: 'Innovation Program 2024',
            description: 'Advanced technology innovation program for 2024',
            nationalAlignment: 'NDPIII, DigitalRoadmap2023_2028',
            focusAreas: ['AI', 'IoT', 'Renewable Energy'],
            phases: ['planning', 'development']
        );

        $this->mockRepository
            ->expects($this->once())
            ->method('findByName')
            ->with('Innovation Program 2024')
            ->willReturn(null); // No existing program with same name

        $this->mockRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Program $program) {
                return $program->getName() === 'Innovation Program 2024' &&
                       $program->getDescription() === 'Advanced technology innovation program for 2024' &&
                       $program->getNationalAlignment() === 'NDPIII, DigitalRoadmap2023_2028' &&
                       $program->getFocusAreas() === ['AI', 'IoT', 'Renewable Energy'];
            }));

        $programId = $this->useCase->execute($dto);

        $this->assertIsString($programId);
        $this->assertNotEmpty($programId);
    }

    public function test_throws_exception_when_program_name_already_exists()
    {
        $dto = new CreateProgramDTO(
            name: 'Existing Program',
            description: 'Valid description here',
            nationalAlignment: 'NDPIII',
            focusAreas: ['AI'],
            phases: ['planning']
        );

        $existingProgram = new Program(
            id: 'existing-123',
            name: 'Existing Program',
            description: 'Existing description',
            nationalAlignment: 'NDPIII',
            focusAreas: ['AI'],
            phases: ['planning']
        );

        $this->mockRepository
            ->expects($this->once())
            ->method('findByName')
            ->with('Existing Program')
            ->willReturn($existingProgram);

        $this->mockRepository
            ->expects($this->never())
            ->method('save');

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Program.Name already exists');

        $this->useCase->execute($dto);
    }

    public function test_throws_exception_for_invalid_program_data()
    {
        $dto = new CreateProgramDTO(
            name: 'AB', // Too short
            description: 'Valid description here',
            nationalAlignment: 'NDPIII',
            focusAreas: ['AI'],
            phases: ['planning']
        );

        $this->mockRepository
            ->expects($this->once())
            ->method('findByName')
            ->with('AB')
            ->willReturn(null);

        $this->mockRepository
            ->expects($this->never())
            ->method('save');

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Program name must be at least 3 characters long');

        $this->useCase->execute($dto);
    }

    public function test_case_insensitive_name_uniqueness_check()
    {
        $dto = new CreateProgramDTO(
            name: 'INNOVATION PROGRAM', // Different case
            description: 'Valid description here',
            nationalAlignment: 'NDPIII',
            focusAreas: ['AI'],
            phases: ['planning']
        );

        $existingProgram = new Program(
            id: 'existing-123',
            name: 'innovation program', // Lower case
            description: 'Existing description',
            nationalAlignment: 'NDPIII',
            focusAreas: ['AI'],
            phases: ['planning']
        );

        $this->mockRepository
            ->expects($this->once())
            ->method('findByName')
            ->with('INNOVATION PROGRAM')
            ->willReturn($existingProgram); // Repository should handle case-insensitive search

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Program.Name already exists');

        $this->useCase->execute($dto);
    }
}
