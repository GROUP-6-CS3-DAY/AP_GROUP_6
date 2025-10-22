<?php

namespace Tests\Unit\Application\UseCases;

use App\Application\UseCases\DeleteOutcomeUseCase;
use App\Domain\Repositories\OutcomeRepositoryInterface;
use App\Domain\Repositories\ProjectRepositoryInterface;
use App\Domain\Entities\Outcome;
use App\Domain\Entities\Project;
use App\Domain\ValueObjects\CommercializationStatus;
use App\Application\Exceptions\OutcomeNotFoundException;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class DeleteOutcomeUseCaseTest extends TestCase
{
    private OutcomeRepositoryInterface|MockObject $mockOutcomeRepository;
    private ProjectRepositoryInterface|MockObject $mockProjectRepository;
    private DeleteOutcomeUseCase $useCase;

    protected function setUp(): void
    {
        $this->mockOutcomeRepository = $this->createMock(OutcomeRepositoryInterface::class);
        $this->mockProjectRepository = $this->createMock(ProjectRepositoryInterface::class);
        $this->useCase = new DeleteOutcomeUseCase(
            $this->mockOutcomeRepository,
            $this->mockProjectRepository
        );
    }

    public function test_can_delete_outcome_when_exists()
    {
        $outcomeId = 'outcome-123';
        $projectId = 'project-123';
        
        // Create mock outcome with non-commercialized status
        $mockOutcome = $this->createMock(Outcome::class);
        $mockCommercializationStatus = $this->createMock(CommercializationStatus::class);
        $mockCommercializationStatus->method('getValue')->willReturn('ready');
        $mockOutcome->method('getCommercializationStatus')->willReturn($mockCommercializationStatus);
        $mockOutcome->method('getProjectId')->willReturn($projectId);
        $mockOutcome->method('isHighImpact')->willReturn(false);
        $mockOutcome->method('getQualityCertification')->willReturn('');

        // Mock project that is not completed
        $mockProject = $this->createMock(Project::class);
        $mockProject->method('isCompleted')->willReturn(false);

        $this->mockOutcomeRepository
            ->expects($this->once())
            ->method('findById')
            ->with($outcomeId)
            ->willReturn($mockOutcome);

        $this->mockProjectRepository
            ->expects($this->once())
            ->method('findById')
            ->with($projectId)
            ->willReturn($mockProject);

        $this->mockOutcomeRepository
            ->expects($this->once())
            ->method('delete')
            ->with($outcomeId);

        $this->useCase->execute($outcomeId);
    }

    public function test_cannot_delete_outcome_with_commercialized_status()
    {
        $outcomeId = 'outcome-123';
        
        // Create mock outcome with commercialized status
        $mockOutcome = $this->createMock(Outcome::class);
        $mockCommercializationStatus = $this->createMock(CommercializationStatus::class);
        $mockCommercializationStatus->method('getValue')->willReturn('commercialized');
        $mockOutcome->method('getCommercializationStatus')->willReturn($mockCommercializationStatus);

        $this->mockOutcomeRepository
            ->expects($this->once())
            ->method('findById')
            ->with($outcomeId)
            ->willReturn($mockOutcome);

        $this->mockOutcomeRepository
            ->expects($this->never())
            ->method('delete');

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Cannot delete outcome that has been commercialized');

        $this->useCase->execute($outcomeId);
    }

    public function test_cannot_delete_outcome_from_completed_project()
    {
        $outcomeId = 'outcome-123';
        $projectId = 'project-123';
        
        $mockOutcome = $this->createMock(Outcome::class);
        $mockCommercializationStatus = $this->createMock(CommercializationStatus::class);
        $mockCommercializationStatus->method('getValue')->willReturn('ready');
        $mockOutcome->method('getCommercializationStatus')->willReturn($mockCommercializationStatus);
        $mockOutcome->method('getProjectId')->willReturn($projectId);

        // Mock project that is completed
        $mockProject = $this->createMock(Project::class);
        $mockProject->method('isCompleted')->willReturn(true);

        $this->mockOutcomeRepository
            ->expects($this->once())
            ->method('findById')
            ->with($outcomeId)
            ->willReturn($mockOutcome);

        $this->mockProjectRepository
            ->expects($this->once())
            ->method('findById')
            ->with($projectId)
            ->willReturn($mockProject);

        $this->mockOutcomeRepository
            ->expects($this->never())
            ->method('delete');

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Cannot delete outcomes from completed projects');

        $this->useCase->execute($outcomeId);
    }

    public function test_cannot_delete_high_impact_outcome()
    {
        $outcomeId = 'outcome-123';
        $projectId = 'project-123';
        
        $mockOutcome = $this->createMock(Outcome::class);
        $mockCommercializationStatus = $this->createMock(CommercializationStatus::class);
        $mockCommercializationStatus->method('getValue')->willReturn('ready');
        $mockOutcome->method('getCommercializationStatus')->willReturn($mockCommercializationStatus);
        $mockOutcome->method('getProjectId')->willReturn($projectId);
        $mockOutcome->method('isHighImpact')->willReturn(true);

        $mockProject = $this->createMock(Project::class);
        $mockProject->method('isCompleted')->willReturn(false);

        $this->mockOutcomeRepository
            ->expects($this->once())
            ->method('findById')
            ->with($outcomeId)
            ->willReturn($mockOutcome);

        $this->mockProjectRepository
            ->expects($this->once())
            ->method('findById')
            ->with($projectId)
            ->willReturn($mockProject);

        $this->mockOutcomeRepository
            ->expects($this->never())
            ->method('delete');

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Cannot delete high-impact outcomes');

        $this->useCase->execute($outcomeId);
    }

    public function test_throws_exception_when_outcome_not_found()
    {
        $outcomeId = 'non-existent-123';

        $this->mockOutcomeRepository
            ->expects($this->once())
            ->method('findById')
            ->with($outcomeId)
            ->willReturn(null);

        $this->mockOutcomeRepository
            ->expects($this->never())
            ->method('delete');

        $this->expectException(OutcomeNotFoundException::class);
        $this->expectExceptionMessage('Outcome not found');

        $this->useCase->execute($outcomeId);
    }
}
