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
        $dto = new UpdateOutcomeDTO(
            projectId: 'proj-1',
            title: 'Updated Research Publication',
            description: 'Updated research paper description',
            outcomeType: 'publication',
            qualityCertification: 'Updated Peer Review',
            dateAchieved: '2024-02-15',
            commercializationStatus: 'Ready',
            impact: 'Updated significant impact',
            artifactLink: 'https://example.com/updated-paper.pdf'
        );

        // Mock existing outcome
        $mockOutcome = $this->createMock(Outcome::class);
        $this->mockOutcomeRepository
            ->expects($this->once())
            ->method('findById')
            ->with('outcome-1')
            ->willReturn($mockOutcome);

        // Mock project exists
        $mockProject = $this->createMock(Project::class);
        $this->mockProjectRepository
            ->expects($this->once())
            ->method('findById')
            ->with('proj-1')
            ->willReturn($mockProject);

        // Expect save to be called with a new Outcome entity (since UpdateOutcomeUseCase creates a new one)
        $this->mockOutcomeRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Outcome $outcome) {
                return $outcome->getProjectId() === 'proj-1' &&
                       $outcome->getTitle() === 'Updated Research Publication' &&
                       $outcome->getOutcomeType()->getValue() === 'publication';
            }));

        $this->useCase->execute('outcome-1', $dto);

        // Add assertion to make test not risky
        $this->assertTrue(true, 'Update use case executed successfully');
    }

    public function test_throws_exception_when_outcome_not_found()
    {
        $dto = new UpdateOutcomeDTO(
            projectId: 'proj-1',
            title: 'Updated Title',
            description: 'Updated description',
            outcomeType: 'publication',
            qualityCertification: '',
            dateAchieved: '2024-01-15',
            commercializationStatus: '',
            impact: '',
            artifactLink: ''
        );

        $this->mockOutcomeRepository
            ->expects($this->once())
            ->method('findById')
            ->with('non-existent-outcome')
            ->willReturn(null);

        $this->mockProjectRepository
            ->expects($this->never())
            ->method('findById');

        $this->mockOutcomeRepository
            ->expects($this->never())
            ->method('save');

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Outcome not found');

        $this->useCase->execute('non-existent-outcome', $dto);
    }

    public function test_throws_exception_when_project_not_found()
    {
        $dto = new UpdateOutcomeDTO(
            projectId: 'non-existent-proj',
            title: 'Updated Title',
            description: 'Updated description',
            outcomeType: 'publication',
            qualityCertification: '',
            dateAchieved: '2024-01-15',
            commercializationStatus: '',
            impact: '',
            artifactLink: ''
        );

        // Mock existing outcome
        $mockOutcome = $this->createMock(Outcome::class);
        $this->mockOutcomeRepository
            ->expects($this->once())
            ->method('findById')
            ->with('outcome-1')
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

        $this->useCase->execute('outcome-1', $dto);
    }

    public function test_throws_exception_for_invalid_update_data()
    {
        $dto = new UpdateOutcomeDTO(
            projectId: 'proj-1',
            title: 'AB', // Too short - should trigger validation in Outcome entity
            description: 'Valid description',
            outcomeType: 'publication',
            qualityCertification: '',
            dateAchieved: '2024-01-15',
            commercializationStatus: '',
            impact: '',
            artifactLink: ''
        );

        // Mock existing outcome
        $mockOutcome = $this->createMock(Outcome::class);
        $this->mockOutcomeRepository
            ->expects($this->once())
            ->method('findById')
            ->with('outcome-1')
            ->willReturn($mockOutcome);

        // Mock project exists
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

        $this->useCase->execute('outcome-1', $dto);
    }
}
