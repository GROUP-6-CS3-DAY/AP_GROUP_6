<?php

namespace Tests\Unit\Domain\Entities;

use PHPUnit\Framework\TestCase;
use App\Domain\Entities\Service;
use DomainException;

class ServiceTest extends TestCase
{
    public function test_can_create_service_with_valid_data()
    {
        $service = new Service(
            id: 'service-1',
            facilityId: 'facility-1',
            name: 'Test Service',
            description: 'Test Description',
            category: 'testing',
            skillType: 'hardware'
        );

        $this->assertEquals('service-1', $service->getId());
        $this->assertEquals('facility-1', $service->getFacilityId());
        $this->assertEquals('Test Service', $service->getName());
        $this->assertEquals('Test Description', $service->getDescription());
        $this->assertEquals('testing', $service->getCategory());
        $this->assertEquals('hardware', $service->getSkillType());
    }

    public function test_throws_exception_when_facility_id_is_empty()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Service.FacilityId is required');

        new Service(
            id: 'service-1',
            facilityId: '',
            name: 'Test Service',
            description: 'Test Description',
            category: 'testing',
            skillType: 'hardware'
        );
    }

    public function test_throws_exception_when_name_is_empty()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Service.Name is required');

        new Service(
            id: 'service-1',
            facilityId: 'facility-1',
            name: '',
            description: 'Test Description',
            category: 'testing',
            skillType: 'hardware'
        );
    }

    public function test_throws_exception_when_category_is_empty()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Service.Category is required');

        new Service(
            id: 'service-1',
            facilityId: 'facility-1',
            name: 'Test Service',
            description: 'Test Description',
            category: '',
            skillType: 'hardware'
        );
    }

    public function test_throws_exception_when_skill_type_is_empty()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Service.SkillType is required');

        new Service(
            id: 'service-1',
            facilityId: 'facility-1',
            name: 'Test Service',
            description: 'Test Description',
            category: 'testing',
            skillType: ''
        );
    }

    public function test_throws_exception_for_invalid_category()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Invalid service category: invalid_category');

        new Service(
            id: 'service-1',
            facilityId: 'facility-1',
            name: 'Test Service',
            description: 'Test Description',
            category: 'invalid_category',
            skillType: 'hardware'
        );
    }

    public function test_throws_exception_for_invalid_skill_type()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Invalid skill type: invalid_skill_type');

        new Service(
            id: 'service-1',
            facilityId: 'facility-1',
            name: 'Test Service',
            description: 'Test Description',
            category: 'testing',
            skillType: 'invalid_skill_type'
        );
    }

    public function test_throws_exception_for_duplicate_name_in_facility()
    {
        $existingServiceNames = ['Test Service', 'Another Service'];

        $service = new Service(
            id: 'service-1',
            facilityId: 'facility-1',
            name: 'Test Service',
            description: 'Test Description',
            category: 'testing',
            skillType: 'hardware'
        );

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('A service with this name already exists in this facility');

        $service->validateNameUniquenessInFacility($existingServiceNames);
    }

    public function test_can_be_deleted_when_no_active_projects()
    {
        $service = new Service(
            id: 'service-1',
            facilityId: 'facility-1',
            name: 'Test Service',
            description: 'Test Description',
            category: 'testing',
            skillType: 'hardware'
        );

        $activeProjects = [];
        $this->assertTrue($service->canBeDeleted($activeProjects));
    }

    public function test_cannot_be_deleted_when_active_projects_exist()
    {
        $service = new Service(
            id: 'service-1',
            facilityId: 'facility-1',
            name: 'Test Service',
            description: 'Test Description',
            category: 'testing',
            skillType: 'hardware'
        );

        $activeProjects = ['project-1'];
        $this->assertFalse($service->canBeDeleted($activeProjects));

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Service in use by Project testing requirements');

        $service->validateDeletion($activeProjects);
    }

    public function test_can_update_service_data()
    {
        $service = new Service(
            id: 'service-1',
            facilityId: 'facility-1',
            name: 'Test Service',
            description: 'Test Description',
            category: 'testing',
            skillType: 'hardware'
        );

        $service->update([
            'name' => 'Updated Service',
            'description' => 'Updated Description',
            'category' => 'training'
        ]);

        $this->assertEquals('Updated Service', $service->getName());
        $this->assertEquals('Updated Description', $service->getDescription());
        $this->assertEquals('training', $service->getCategory());
        $this->assertEquals('hardware', $service->getSkillType()); // Unchanged
    }

    public function test_can_identify_service_types()
    {
        $hardwareService = new Service(
            id: 'service-1',
            facilityId: 'facility-1',
            name: 'Hardware Service',
            description: 'Test Description',
            category: 'testing',
            skillType: 'hardware'
        );

        $softwareService = new Service(
            id: 'service-2',
            facilityId: 'facility-1',
            name: 'Software Service',
            description: 'Test Description',
            category: 'testing',
            skillType: 'software'
        );

        $this->assertTrue($hardwareService->isHardwareRelated());
        $this->assertFalse($hardwareService->isSoftwareRelated());
        $this->assertFalse($softwareService->isHardwareRelated());
        $this->assertTrue($softwareService->isSoftwareRelated());
    }

    public function test_can_identify_service_categories()
    {
        $testingService = new Service(
            id: 'service-1',
            facilityId: 'facility-1',
            name: 'Testing Service',
            description: 'Test Description',
            category: 'testing',
            skillType: 'hardware'
        );

        $trainingService = new Service(
            id: 'service-2',
            facilityId: 'facility-1',
            name: 'Training Service',
            description: 'Test Description',
            category: 'training',
            skillType: 'hardware'
        );

        $prototypingService = new Service(
            id: 'service-3',
            facilityId: 'facility-1',
            name: 'Prototyping Service',
            description: 'Test Description',
            category: 'prototyping',
            skillType: 'hardware'
        );

        $this->assertTrue($testingService->isTestingService());
        $this->assertFalse($testingService->isTrainingService());
        $this->assertFalse($testingService->isPrototypingService());

        $this->assertFalse($trainingService->isTestingService());
        $this->assertTrue($trainingService->isTrainingService());
        $this->assertFalse($trainingService->isPrototypingService());

        $this->assertFalse($prototypingService->isTestingService());
        $this->assertFalse($prototypingService->isTrainingService());
        $this->assertTrue($prototypingService->isPrototypingService());
    }

    public function test_can_support_project_requirements()
    {
        $service = new Service(
            id: 'service-1',
            facilityId: 'facility-1',
            name: 'Testing Service',
            description: 'Test Description',
            category: 'testing',
            skillType: 'hardware'
        );

        $this->assertTrue($service->supportsProjectRequirements(['testing', 'prototyping']));
        $this->assertFalse($service->supportsProjectRequirements(['training', 'consultation']));
    }

    public function test_can_get_display_names()
    {
        $service = new Service(
            id: 'service-1',
            facilityId: 'facility-1',
            name: 'Testing Service',
            description: 'Test Description',
            category: 'testing',
            skillType: 'hardware'
        );

        $this->assertEquals('Testing', $service->getCategoryDisplayName());
        $this->assertEquals('Hardware', $service->getSkillTypeDisplayName());
    }
}
