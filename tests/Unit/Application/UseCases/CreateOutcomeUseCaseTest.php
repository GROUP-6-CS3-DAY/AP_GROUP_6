<?php

namespace Tests\Unit\Application\UseCases;

use App\Application\UseCases\CreateOutcomeUseCase;
use App\Application\DTOs\CreateOutcomeDTO;
use App\Domain\Repositories\OutcomeRepositoryInterface;
use App\Domain\Repositories\ProjectRepositoryInterface;
use App\Domain\Entities\Outcome;
use App\Domain\Entities\Project;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class CreateOutcomeUseCaseTest extends TestCase
{
    private OutcomeRepositoryInterface|MockObject $mockOutcomeRepository;
    private ProjectRepositoryInterface|MockObject $mockProjectRepository;
    private CreateOutcomeUseCase $useCase;

    protected function setUp(): void
    {
        $this->mockOutcomeRepository = $this->createMock(OutcomeRepositoryInterface::class);
        $this->mockProjectRepository = $this->createMock(ProjectRepositoryInterface::class);
        $this->useCase = new CreateOutcomeUseCase($this->mockOutcomeRepository, $this->mockProjectRepository);
    }

    public function test_can_create_outcome_with_valid_data()
    {
        $dto = new CreateOutcomeDTO(
            projectId: 'proj-1',
            title: 'Research Publication',
            description: 'Published research paper on AI innovations',
            outcomeType: 'publication',
            qualityCertification: 'Peer Reviewed',
            dateAchieved: '2024-01-15',
            commercializationStatus: 'not_applicable',
            impact: 'Significant contribution to AI research field',
            artifactLink: 'https://example.com/paper.pdf'
        );

        // Mock project exists
        $mockProject = $this->createMock(Project::class);
        $this->mockProjectRepository
            ->expects($this->once())
            ->method('findById')
            ->with('proj-1')
            ->willReturn($mockProject);

        $this->mockOutcomeRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Outcome $outcome) {
                return $outcome->getProjectId() === 'proj-1' &&
                       $outcome->getTitle() === 'Research Publication' &&
                       $outcome->getOutcomeType()->getValue() === 'publication';
            }));

        $outcomeId = $this->useCase->execute($dto);

        $this->assertIsString($outcomeId);
        $this->assertNotEmpty($outcomeId);
    }

    public function test_throws_exception_when_project_not_found()
    {
        $dto = new CreateOutcomeDTO(
            projectId: 'non-existent-proj',
            title: 'Research Publication',
            description: 'Published research paper',
            outcomeType: 'publication',
            qualityCertification: '',
            dateAchieved: '2024-01-15',
            commercializationStatus: 'not_applicable',
            impact: '',
            artifactLink: ''
        );

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

        $this->useCase->execute($dto);
    }

    public function test_throws_exception_for_invalid_outcome_data()
    {
        $dto = new CreateOutcomeDTO(
            projectId: 'proj-1',
            title: 'AB', // Too short
            description: 'Valid description',
            outcomeType: 'publication',
            qualityCertification: '',
            dateAchieved: '2024-01-15',
            commercializationStatus: 'not_applicable',
            impact: '',
            artifactLink: ''
        );

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

        $this->useCase->execute($dto);
    }

    public function test_throws_exception_for_future_date()
    {
        $futureDate = date('Y-m-d', strtotime('+1 year'));
        
        $dto = new CreateOutcomeDTO(
            projectId: 'proj-1',
            title: 'Future Outcome',
            description: 'Outcome with future date',
            outcomeType: 'publication',
            qualityCertification: '',
            dateAchieved: $futureDate,
            commercializationStatus: 'not_applicable',
            impact: '',
            artifactLink: ''
        );

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
        $this->expectExceptionMessage('Outcome date achieved cannot be in the future');

        $this->useCase->execute($dto);
    }
}
