<?php

namespace Tests\Unit\Domain\Entities;

use PHPUnit\Framework\TestCase;
use App\Domain\Entities\Project;
use App\Domain\ValueObjects\InnovationFocus;
use App\Domain\ValueObjects\PrototypeStage;
use App\Domain\ValueObjects\ProjectStatus;

class ProjectTest extends TestCase
{
    public function test_can_create_project_with_valid_data()
    {
        $project = new Project(
            id: 'proj-123',
            programId: 'prog-1',
            facilityId: 'fac-1',
            title: 'Test Project Title',
            natureOfProject: 'Research and Development',
            description: 'This is a valid description for testing purposes',
            innovationFocus: new InnovationFocus('product'),
            prototypeStage: new PrototypeStage('concept'),
            testingRequirements: 'Basic testing requirements',
            commercializationPlan: 'Future commercialization plan',
            status: new ProjectStatus('planning'),
            participants: [],
            outcomes: [],
            technicalRequirements: []
        );

        $this->assertEquals('proj-123', $project->getId());
        $this->assertEquals('prog-1', $project->getProgramId());
        $this->assertEquals('fac-1', $project->getFacilityId());
        $this->assertEquals('Test Project Title', $project->getTitle());
        $this->assertEquals('planning', $project->getStatus()->getValue());
    }

    public function test_required_associations_validation()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Project.ProgramId and Project.FacilityId are required');

        new Project(
            id: 'proj-123',
            programId: '', // Empty program ID should fail
            facilityId: 'fac-1',
            title: 'Test Project Title',
            natureOfProject: 'Research',
            description: 'Valid description for testing',
            innovationFocus: new InnovationFocus('product'),
            prototypeStage: new PrototypeStage('concept'),
            testingRequirements: 'Testing requirements',
            commercializationPlan: 'Commercialization plan'
        );
    }

    public function test_team_assignment_validation()
    {
        $project = new Project(
            id: 'proj-123',
            programId: 'prog-1',
            facilityId: 'fac-1',
            title: 'Test Project Title',
            natureOfProject: 'Research',
            description: 'Valid description for testing',
            innovationFocus: new InnovationFocus('product'),
            prototypeStage: new PrototypeStage('concept'),
            testingRequirements: 'Testing requirements',
            commercializationPlan: 'Commercialization plan',
            participants: [] // No participants
        );

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Project must have at least one team member assigned');

        $project->validateTeamAssignment();
    }

    public function test_outcome_validation_for_completed_status()
    {
        $project = new Project(
            id: 'proj-123',
            programId: 'prog-1',
            facilityId: 'fac-1',
            title: 'Test Project Title',
            natureOfProject: 'Research',
            description: 'Valid description for testing',
            innovationFocus: new InnovationFocus('product'),
            prototypeStage: new PrototypeStage('concept'),
            testingRequirements: 'Testing requirements',
            commercializationPlan: 'Commercialization plan',
            outcomes: [] // No outcomes
        );

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Completed projects must have at least one documented outcome');

        $project->update(['status' => 'completed']);
    }

    public function test_name_uniqueness_validation_in_program()
    {
        $project = new Project(
            id: 'proj-123',
            programId: 'prog-1',
            facilityId: 'fac-1',
            title: 'Duplicate Project Name',
            natureOfProject: 'Research',
            description: 'Valid description for testing',
            innovationFocus: new InnovationFocus('product'),
            prototypeStage: new PrototypeStage('concept'),
            testingRequirements: 'Testing requirements',
            commercializationPlan: 'Commercialization plan'
        );

        $existingProjectNames = ['duplicate project name', 'another project']; // Case insensitive

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('A project with this name already exists in this program');

        $project->validateNameUniquenessInProgram($existingProjectNames);
    }

    public function test_facility_compatibility_validation()
    {
        $project = new Project(
            id: 'proj-123',
            programId: 'prog-1',
            facilityId: 'fac-1',
            title: 'Test Project Title',
            natureOfProject: 'Research',
            description: 'Valid description for testing',
            innovationFocus: new InnovationFocus('product'),
            prototypeStage: new PrototypeStage('concept'),
            testingRequirements: 'Testing requirements',
            commercializationPlan: 'Commercialization plan',
            technicalRequirements: ['cnc_machining', 'advanced_testing'] // Requires these capabilities
        );

        $facilityCapabilities = ['cnc_machining', '3d_printing']; // Missing 'advanced_testing'

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Project requirements not compatible with facility capabilities');

        $project->validateFacilityCompatibility($facilityCapabilities);
    }

    public function test_add_and_remove_participants()
    {
        $project = new Project(
            id: 'proj-123',
            programId: 'prog-1',
            facilityId: 'fac-1',
            title: 'Test Project Title',
            natureOfProject: 'Research',
            description: 'Valid description for testing',
            innovationFocus: new InnovationFocus('product'),
            prototypeStage: new PrototypeStage('concept'),
            testingRequirements: 'Testing requirements',
            commercializationPlan: 'Commercialization plan',
            participants: ['participant-1']
        );

        // Add participant
        $project->addParticipant('participant-2');
        $this->assertEquals(2, $project->getParticipantCount());

        // Remove participant but maintain at least one
        $project->removeParticipant('participant-2');
        $this->assertEquals(1, $project->getParticipantCount());

        // Try to remove last participant - should fail
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Project must have at least one team member assigned');

        $project->removeParticipant('participant-1');
    }

    public function test_project_business_logic_methods()
    {
        $project = new Project(
            id: 'proj-123',
            programId: 'prog-1',
            facilityId: 'fac-1',
            title: 'Test Project Title',
            natureOfProject: 'Research',
            description: 'Valid description for testing',
            innovationFocus: new InnovationFocus('product'),
            prototypeStage: new PrototypeStage('production'),
            testingRequirements: 'Testing requirements',
            commercializationPlan: 'Detailed commercialization plan',
            participants: ['p1', 'p2'],
            outcomes: ['o1', 'o2', 'o3']
        );

        $this->assertEquals(2, $project->getParticipantCount());
        $this->assertEquals(3, $project->getOutcomeCount());
        $this->assertTrue($project->isReadyForCommercialization());
        $this->assertTrue($project->canBeCompleted());
    }
}
