<?php

namespace Tests\Unit\Domain\Entities;

use PHPUnit\Framework\TestCase;
use App\Domain\Entities\Program;
use App\Domain\ValueObjects\ProgramPhase;

class ProgramTest extends TestCase
{
    public function test_can_create_program_with_valid_data()
    {
        $program = new Program(
            id: 'prog-123',
            name: 'Innovation Program 2024',
            description: 'Advanced technology innovation program',
            nationalAlignment: 'NDPIII, DigitalRoadmap2023_2028',
            focusAreas: ['AI', 'IoT', 'Renewable Energy'],
            phases: ['planning', 'development'],
            projects: []
        );

        $this->assertEquals('prog-123', $program->getId());
        $this->assertEquals('Innovation Program 2024', $program->getName());
        $this->assertEquals('Advanced technology innovation program', $program->getDescription());
        $this->assertEquals('NDPIII, DigitalRoadmap2023_2028', $program->getNationalAlignment());
        $this->assertEquals(['AI', 'IoT', 'Renewable Energy'], $program->getFocusAreas());
        $this->assertCount(2, $program->getPhases());
        $this->assertTrue($program->isActive());
        $this->assertTrue($program->canAcceptProjects());
    }

    public function test_program_name_is_required()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Program.Name is required');

        new Program(
            id: 'prog-123',
            name: '', // Empty name should fail
            description: 'Valid description',
            nationalAlignment: 'NDPIII',
            focusAreas: ['AI'],
            phases: ['planning']
        );
    }

    public function test_program_name_must_be_at_least_3_characters()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Program name must be at least 3 characters long');

        new Program(
            id: 'prog-123',
            name: 'AB', // Too short
            description: 'Valid description',
            nationalAlignment: 'NDPIII',
            focusAreas: ['AI'],
            phases: ['planning']
        );
    }

    public function test_program_description_is_required()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Program.Description is required');

        new Program(
            id: 'prog-123',
            name: 'Valid Program Name',
            description: '', // Empty description should fail
            nationalAlignment: 'NDPIII',
            focusAreas: ['AI'],
            phases: ['planning']
        );
    }

    public function test_program_description_must_be_at_least_10_characters()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Program description must be at least 10 characters long');

        new Program(
            id: 'prog-123',
            name: 'Valid Program Name',
            description: 'Short', // Too short
            nationalAlignment: 'NDPIII',
            focusAreas: ['AI'],
            phases: ['planning']
        );
    }

    public function test_national_alignment_required_when_focus_areas_specified()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Program.NationalAlignment must include at least one recognized alignment when FocusAreas are specified');

        new Program(
            id: 'prog-123',
            name: 'Valid Program Name',
            description: 'Valid description here',
            nationalAlignment: '', // Empty alignment with focus areas should fail
            focusAreas: ['AI', 'IoT'], // Non-empty focus areas
            phases: ['planning']
        );
    }

    public function test_national_alignment_must_contain_valid_tokens_when_focus_areas_specified()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Program.NationalAlignment must include at least one recognized alignment when FocusAreas are specified');

        new Program(
            id: 'prog-123',
            name: 'Valid Program Name',
            description: 'Valid description here',
            nationalAlignment: 'InvalidAlignment, AnotherInvalid', // Invalid alignment tokens
            focusAreas: ['AI', 'IoT'], // Non-empty focus areas
            phases: ['planning']
        );
    }

    public function test_valid_national_alignment_tokens_accepted()
    {
        // Test NDPIII
        $program1 = new Program(
            id: 'prog-123',
            name: 'Valid Program Name',
            description: 'Valid description here',
            nationalAlignment: 'NDPIII',
            focusAreas: ['AI'],
            phases: ['planning']
        );
        $this->assertNotNull($program1);

        // Test DigitalRoadmap2023_2028
        $program2 = new Program(
            id: 'prog-124',
            name: 'Valid Program Name 2',
            description: 'Valid description here',
            nationalAlignment: 'DigitalRoadmap2023_2028',
            focusAreas: ['IoT'],
            phases: ['planning']
        );
        $this->assertNotNull($program2);

        // Test 4IR
        $program3 = new Program(
            id: 'prog-125',
            name: 'Valid Program Name 3',
            description: 'Valid description here',
            nationalAlignment: '4IR',
            focusAreas: ['Blockchain'],
            phases: ['planning']
        );
        $this->assertNotNull($program3);

        // Test multiple valid tokens
        $program4 = new Program(
            id: 'prog-126',
            name: 'Valid Program Name 4',
            description: 'Valid description here',
            nationalAlignment: 'NDPIII, DigitalRoadmap2023_2028, 4IR',
            focusAreas: ['AI', 'IoT'],
            phases: ['planning']
        );
        $this->assertNotNull($program4);
    }

    public function test_national_alignment_can_be_empty_when_no_focus_areas()
    {
        $program = new Program(
            id: 'prog-123',
            name: 'Valid Program Name',
            description: 'Valid description here',
            nationalAlignment: '', // Empty is OK when no focus areas
            focusAreas: [], // Empty focus areas allowed during construction
            phases: ['planning']
        );

        $this->assertEquals('', $program->getNationalAlignment());
        $this->assertEmpty($program->getFocusAreas());
    }

    public function test_program_must_have_at_least_one_focus_area_when_updated()
    {
        // Create a program with some focus areas first
        $program = new Program(
            id: 'prog-123',
            name: 'Valid Program Name',
            description: 'Valid description here',
            nationalAlignment: 'NDPIII',
            focusAreas: ['AI'], // Start with focus areas
            phases: ['planning']
        );

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Program must have at least one focus area');

        // Now try to update with empty focus areas
        $program->update([
            'focus_areas' => [] // Explicitly setting empty focus areas should fail during update
        ]);
    }

    public function test_update_validates_name_requirements()
    {
        $program = new Program(
            id: 'prog-123',
            name: 'Valid Program Name',
            description: 'Valid description here',
            nationalAlignment: 'NDPIII',
            focusAreas: ['AI'],
            phases: ['planning']
        );

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Program name must be at least 3 characters long');

        $program->update(['name' => 'AB']); // Too short
    }

    public function test_update_validates_description_requirements()
    {
        $program = new Program(
            id: 'prog-123',
            name: 'Valid Program Name',
            description: 'Valid description here',
            nationalAlignment: 'NDPIII',
            focusAreas: ['AI'],
            phases: ['planning']
        );

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Program description must be at least 10 characters long');

        $program->update(['description' => 'Short']); // Too short
    }

    public function test_successful_update_changes_properties()
    {
        $program = new Program(
            id: 'prog-123',
            name: 'Original Name',
            description: 'Original description here',
            nationalAlignment: 'NDPIII',
            focusAreas: ['AI'],
            phases: ['planning']
        );

        $program->update([
            'name' => 'Updated Program Name',
            'description' => 'Updated description with more details',
            'national_alignment' => 'DigitalRoadmap2023_2028',
            'focus_areas' => ['AI', 'IoT', 'Blockchain'],
            'phases' => ['planning', 'development', 'implementation']
        ]);

        $this->assertEquals('Updated Program Name', $program->getName());
        $this->assertEquals('Updated description with more details', $program->getDescription());
        $this->assertEquals('DigitalRoadmap2023_2028', $program->getNationalAlignment());
        $this->assertEquals(['AI', 'IoT', 'Blockchain'], $program->getFocusAreas());
        $this->assertCount(3, $program->getPhases());
    }

    public function test_program_business_logic_methods()
    {
        $program = new Program(
            id: 'prog-123',
            name: 'Test Program',
            description: 'Test description here',
            nationalAlignment: 'NDPIII',
            focusAreas: ['AI', 'IoT'],
            phases: ['planning', 'development'],
            projects: ['project1', 'project2'] // Pass actual project data to get correct count
        );

        $this->assertEquals(2, $program->getProjectCount());
        $this->assertEquals('AI, IoT', $program->getFocusAreasAsString());
        $this->assertStringContainsString('Planning', $program->getPhasesAsString());
        $this->assertTrue($program->isActive());
        $this->assertTrue($program->canAcceptProjects());
    }

    public function test_program_is_not_active_without_phases_or_focus_areas()
    {
        $program = new Program(
            id: 'prog-123',
            name: 'Test Program',
            description: 'Test description here',
            nationalAlignment: '',
            focusAreas: [], // Empty focus areas are allowed during construction
            phases: []
        );

        $this->assertFalse($program->isActive());
        $this->assertFalse($program->canAcceptProjects());
    }

    public function test_program_deletion_validation()
    {
        // Program without projects can be deleted
        $programWithoutProjects = new Program(
            id: 'prog-123',
            name: 'Test Program',
            description: 'Test description here',
            nationalAlignment: 'NDPIII',
            focusAreas: ['AI'],
            phases: ['planning'],
            projects: [] // No projects
        );

        $this->assertTrue($programWithoutProjects->canBeDeleted());
        // Should not throw exception
        $programWithoutProjects->validateDeletion();

        // Program with projects cannot be deleted
        $programWithProjects = new Program(
            id: 'prog-124',
            name: 'Test Program 2',
            description: 'Test description here',
            nationalAlignment: 'NDPIII',
            focusAreas: ['AI'],
            phases: ['planning'],
            projects: ['project1'] // Has projects
        );

        $this->assertFalse($programWithProjects->canBeDeleted());
        
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Program has Projects; archive or reassign before delete');
        $programWithProjects->validateDeletion();
    }
}
