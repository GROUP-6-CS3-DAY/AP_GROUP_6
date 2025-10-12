<?php

namespace Tests\Unit\Application\UseCases;

use App\Application\UseCases\DeleteProjectUseCase;
use App\Domain\Repositories\ProjectRepositoryInterface;
use App\Domain\Entities\Project;
use App\Domain\ValueObjects\InnovationFocus;
use App\Domain\ValueObjects\PrototypeStage;
use App\Domain\ValueObjects\ProjectStatus;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class DeleteProjectUseCaseTest extends TestCase
{
    private ProjectRepositoryInterface|MockObject $mockRepository;
    private DeleteProjectUseCase $useCase;

    protected function setUp(): void
    {
        $this->mockRepository = $this->createMock(ProjectRepositoryInterface::class);
        $this->useCase = new DeleteProjectUseCase($this->mockRepository);
    }

    public function test_can_delete_project_without_outcomes_and_inactive_status()
    {
        $projectId = 'proj-123';
        
        $project = new Project(
            id: $projectId,
            programId: 'prog-1',
            facilityId: 'fac-1',
            title: 'Test Project',
            natureOfProject: 'Research project',
            description: 'A test project for deletion',
            innovationFocus: new InnovationFocus('product'),
            prototypeStage: new PrototypeStage('concept'),
            testingRequirements: 'Basic testing',
            commercializationPlan: 'Future commercialization',
            status: new ProjectStatus('planning'),
            participants: [],
            outcomes: [] // No outcomes
        );

        $this->mockRepository
            ->expects($this->once())
            ->method('findById')
            ->with($projectId)
            ->willReturn($project);

        $this->mockRepository
            ->expects($this->once())
            ->method('delete')
            ->with($projectId);

        $this->useCase->execute($projectId);
    }

    public function test_cannot_delete_project_with_outcomes()
    {
        $projectId = 'proj-123';
        
        $project = new Project(
            id: $projectId,
            programId: 'prog-1',
            facilityId: 'fac-1',
            title: 'Test Project',
            natureOfProject: 'Research project',
            description: 'A test project for deletion',
            innovationFocus: new InnovationFocus('product'),
            prototypeStage: new PrototypeStage('concept'),
            testingRequirements: 'Basic testing',
            commercializationPlan: 'Future commercialization',
            status: new ProjectStatus('planning'),
            participants: [],
            outcomes: ['outcome-1'] // Has outcomes
        );

        $this->mockRepository
            ->expects($this->once())
            ->method('findById')
            ->with($projectId)
            ->willReturn($project);

        $this->mockRepository
            ->expects($this->never())
            ->method('delete');

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Cannot delete project with existing outcomes');

        $this->useCase->execute($projectId);
    }

    public function test_cannot_delete_active_project()
    {
        $projectId = 'proj-123';
        
        $project = new Project(
            id: $projectId,
            programId: 'prog-1',
            facilityId: 'fac-1',
            title: 'Test Project',
            natureOfProject: 'Research project',
            description: 'A test project for deletion',
            innovationFocus: new InnovationFocus('product'),
            prototypeStage: new PrototypeStage('concept'),
            testingRequirements: 'Basic testing',
            commercializationPlan: 'Future commercialization',
            status: new ProjectStatus('active'), // Active status
            participants: [],
            outcomes: []
        );

        $this->mockRepository
            ->expects($this->once())
            ->method('findById')
            ->with($projectId)
            ->willReturn($project);

        $this->mockRepository
            ->expects($this->never())
            ->method('delete');

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Cannot delete active project');

        $this->useCase->execute($projectId);
    }

    public function test_cannot_delete_completed_project()
    {
        $projectId = 'proj-123';
        
        $project = new Project(
            id: $projectId,
            programId: 'prog-1',
            facilityId: 'fac-1',
            title: 'Test Project',
            natureOfProject: 'Research project',
            description: 'A test project for deletion',
            innovationFocus: new InnovationFocus('product'),
            prototypeStage: new PrototypeStage('production'),
            testingRequirements: 'Basic testing',
            commercializationPlan: 'Future commercialization',
            status: new ProjectStatus('completed'), // Completed status
            participants: [],
            outcomes: []
        );

        $this->mockRepository
            ->expects($this->once())
            ->method('findById')
            ->with($projectId)
            ->willReturn($project);

        $this->mockRepository
            ->expects($this->never())
            ->method('delete');

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Cannot delete completed project');

        $this->useCase->execute($projectId);
    }

    public function test_throws_exception_when_project_not_found()
    {
        $projectId = 'non-existent-123';

        $this->mockRepository
            ->expects($this->once())
            ->method('findById')
            ->with($projectId)
            ->willReturn(null);

        $this->mockRepository
            ->expects($this->never())
            ->method('delete');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Project not found');

        $this->useCase->execute($projectId);
    }
}
