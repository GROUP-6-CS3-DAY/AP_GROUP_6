<?php

namespace Tests\Unit\Application\UseCases;

use App\Application\UseCases\UpdateOutcomeUseCase;
use App\Application\DTOs\UpdateOutcomeDTO;
use App\Domain\Repositories\OutcomeRepositoryInterface;
use App\Domain\Repositories\ProjectRepositoryInterface;
use App\Domain\Entities\Outcome;
use App\Domain\Entities\Project;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class UpdateOutcomeUseCaseTest extends TestCase
{
    private OutcomeRepositoryInterface|MockObject $mockOutcomeRepository;
    private ProjectRepositoryInterface|MockObject $mockProjectRepository;
    private UpdateOutcomeUseCase $useCase;

    protected function setUp(): void
    {
        $this->mockOutcomeRepository = $this->createMock(OutcomeRepositoryInterface::class);
        $this->mockProjectRepository = $this->createMock(ProjectRepositoryInterface::class);
        $this->useCase = new UpdateOutcomeUseCase($this->mockOutcomeRepository, $this->mockProjectRepository);
    }

    public function test_can_update_outcome_with_valid_data()
    {
        $outcomeId = 'outcome-123';
        
        $dto = new UpdateOutcomeDTO(
            projectId: 'proj-1',
            title: 'Updated Research Publication',
            description: 'Updated description of research paper',
            outcomeType: 'publication',
            qualityCertification: 'Peer Reviewed',
            dateAchieved: '2024-01-15',
            commercializationStatus: 'not_applicable',
            impact: 'Enhanced research contribution',
            artifactLink: 'https://example.com/updated-paper.pdf'
        );

        // Mock outcome exists
        $mockOutcome = $this->createMock(Outcome::class);
        $mockOutcome->expects($this->once())->method('update');
        
        $this->mockOutcomeRepository
            ->expects($this->once())
            ->method('findById')
            ->with($outcomeId)
            ->willReturn($mockOutcome);

        // Mock project exists for validation
        $mockProject = $this->createMock(Project::class);
        $this->mockProjectRepository
            ->expects($this->once())
            ->method('findById')
            ->with('proj-1')
            ->willReturn($mockProject);

        $this->mockOutcomeRepository
            ->expects($this->once())
            ->method('save')
            ->with($mockOutcome);

        $this->useCase->execute($outcomeId, $dto);
    }

    public function test_throws_exception_when_outcome_not_found()
    {
        $outcomeId = 'non-existent-123';
        
        $dto = new UpdateOutcomeDTO(
            projectId: 'proj-1',
            title: 'Updated Title',
            description: 'Updated description',
            outcomeType: 'publication',
            qualityCertification: '',
            dateAchieved: '2024-01-15',
            commercializationStatus: 'not_applicable'
        );

        $this->mockOutcomeRepository
            ->expects($this->once())
            ->method('findById')
            ->with($outcomeId)
            ->willReturn(null);

        $this->mockProjectRepository
            ->expects($this->never())
            ->method('findById');

        $this->mockOutcomeRepository
            ->expects($this->never())
            ->method('save');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Outcome not found');

        $this->useCase->execute($outcomeId, $dto);
    }

    public function test_throws_exception_when_project_not_found()
    {
        $outcomeId = 'outcome-123';
        
        $dto = new UpdateOutcomeDTO(
            projectId: 'non-existent-proj',
            title: 'Updated Title',
            description: 'Updated description',
            outcomeType: 'publication',
            qualityCertification: '',
            dateAchieved: '2024-01-15',
            commercializationStatus: 'not_applicable'
        );

        $mockOutcome = $this->createMock(Outcome::class);
        $this->mockOutcomeRepository
            ->expects($this->once())
            ->method('findById')
            ->with($outcomeId)
            ->willReturn($mockOutcome);

        $this->mockProjectRepository
            ->expects($this->once())
            ->method('findById')
            ->with('non-existent-proj')
            ->willReturn(null);

        $this->mockOutcomeRepository
            ->expects($this->never())
            ->method('save');

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Project not found');

        $this->useCase->execute($outcomeId, $dto);
    }

    public function test_throws_exception_for_invalid_update_data()
    {
        $outcomeId = 'outcome-123';
        
        $dto = new UpdateOutcomeDTO(
            projectId: 'proj-1',
            title: 'AB', // Too short
            description: 'Updated description',
            outcomeType: 'publication',
            qualityCertification: '',
            dateAchieved: '2024-01-15',
            commercializationStatus: 'not_applicable'
        );

        $mockOutcome = $this->createMock(Outcome::class);
        $mockOutcome->expects($this->once())
            ->method('update')
            ->willThrowException(new \DomainException('Outcome title must be at least 5 characters long'));
        
        $this->mockOutcomeRepository
            ->expects($this->once())
            ->method('findById')
            ->with($outcomeId)
            ->willReturn($mockOutcome);

        $mockProject = $this->createMock(Project::class);
        $this->mockProjectRepository
            ->expects($this->once())
            ->method('findById')
            ->with('proj-1')
            ->willReturn($mockProject);

        $this->mockOutcomeRepository
            ->expects($this->never())
            ->method('save');

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Outcome title must be at least 5 characters long');

        $this->useCase->execute($outcomeId, $dto);
    }
}
