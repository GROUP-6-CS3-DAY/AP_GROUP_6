<?php

namespace Tests\Unit\Domain\Entities;

use PHPUnit\Framework\TestCase;
use App\Domain\Entities\Service;
use App\Domain\ValueObjects\ServiceCategory;
use App\Domain\ValueObjects\SkillType;

class ServiceTest extends TestCase
{
    public function test_can_create_service_with_valid_data()
    {
        $service = new Service(
            id: 'service-1',
            facilityId: 'facility-1',
            name: 'Test Service',
            description: 'Test Description',
            category: new ServiceCategory('testing'),
            skillType: new SkillType('technical'),
            requirements: ['requirement1', 'requirement2'],
            availabilityStatus: 'available',
            cost: 100.50
        );

        $this->assertEquals('service-1', $service->getId());
        $this->assertEquals('facility-1', $service->getFacilityId());
        $this->assertEquals('Test Service', $service->getName());
        $this->assertEquals('Test Description', $service->getDescription());
        $this->assertEquals('testing', $service->getCategory()->getValue());
        $this->assertEquals('technical', $service->getSkillType()->getValue());
        $this->assertEquals(['requirement1', 'requirement2'], $service->getRequirements());
        $this->assertEquals('available', $service->getAvailabilityStatus());
        $this->assertEquals(100.50, $service->getCost());
    }

    public function test_throws_exception_when_facility_id_is_empty()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Service.FacilityId is required');

        new Service(
            id: 'service-1',
            facilityId: '',
            name: 'Test Service',
            description: 'Test Description',
            category: new ServiceCategory('testing'),
            skillType: new SkillType('technical')
        );
    }

    public function test_throws_exception_when_name_is_empty()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Service.Name is required');

        new Service(
            id: 'service-1',
            facilityId: 'facility-1',
            name: '',
            description: 'Test Description',
            category: new ServiceCategory('testing'),
            skillType: new SkillType('technical')
        );
    }

    public function test_throws_exception_for_invalid_category()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid service category: invalid_category');

        new ServiceCategory('invalid_category');
    }

    public function test_throws_exception_for_invalid_skill_type()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid skill type: invalid_skill_type');

        new SkillType('invalid_skill_type');
    }

    public function test_can_update_service_data()
    {
        $service = new Service(
            id: 'service-1',
            facilityId: 'facility-1',
            name: 'Test Service',
            description: 'Test Description',
            category: new ServiceCategory('testing'),
            skillType: new SkillType('technical')
        );

        $service->update([
            'name' => 'Updated Service',
            'description' => 'Updated Description',
            'cost' => 200.00
        ]);

        $this->assertEquals('Updated Service', $service->getName());
        $this->assertEquals('Updated Description', $service->getDescription());
        $this->assertEquals(200.00, $service->getCost());
        $this->assertEquals('facility-1', $service->getFacilityId()); // Unchanged
    }

    public function test_service_business_logic_methods()
    {
        $service = new Service(
            id: 'service-1',
            facilityId: 'facility-1',
            name: 'Testing Service',
            description: 'Test Description',
            category: new ServiceCategory('testing'),
            skillType: new SkillType('technical'),
            requirements: ['requirement1', 'requirement2'],
            availabilityStatus: 'available'
        );

        $this->assertTrue($service->isAvailable());
        $this->assertTrue($service->isInCategory('testing'));
        $this->assertFalse($service->isInCategory('training'));
        $this->assertTrue($service->requiresSkill('technical'));
        $this->assertFalse($service->requiresSkill('creative'));
        $this->assertTrue($service->hasRequirement('requirement1'));
        $this->assertFalse($service->hasRequirement('requirement3'));
        $this->assertEquals('facility-1|testing service', $service->getFacilityServiceIdentifier());
    }

    public function test_service_unavailable_status()
    {
        $service = new Service(
            id: 'service-1',
            facilityId: 'facility-1',
            name: 'Test Service',
            description: 'Test Description',
            category: new ServiceCategory('testing'),
            skillType: new SkillType('technical'),
            availabilityStatus: 'maintenance'
        );

        $this->assertFalse($service->isAvailable());
    }

    public function test_can_be_requested_by_participant_with_matching_skills()
    {
        $service = new Service(
            id: 'service-1',
            facilityId: 'facility-1',
            name: 'Test Service',
            description: 'Test Description',
            category: new ServiceCategory('testing'),
            skillType: new SkillType('technical')
        );

        $participantSkills = ['technical', 'analytical'];
        $this->assertTrue($service->canBeRequestedBy($participantSkills));

        $participantSkillsWithoutMatch = ['creative', 'managerial'];
        $this->assertFalse($service->canBeRequestedBy($participantSkillsWithoutMatch));
    }

    public function test_service_categories_and_skill_types()
    {
        $testingService = new Service(
            id: 'service-1',
            facilityId: 'facility-1',
            name: 'Testing Service',
            description: 'Test Description',
            category: new ServiceCategory('testing'),
            skillType: new SkillType('technical')
        );

        $trainingService = new Service(
            id: 'service-2',
            facilityId: 'facility-1',
            name: 'Training Service',
            description: 'Test Description',
            category: new ServiceCategory('training'),
            skillType: new SkillType('managerial')
        );

        $this->assertTrue($testingService->isInCategory('testing'));
        $this->assertFalse($testingService->isInCategory('training'));
        $this->assertTrue($testingService->requiresSkill('technical'));
        $this->assertFalse($testingService->requiresSkill('managerial'));

        $this->assertTrue($trainingService->isInCategory('training'));
        $this->assertFalse($trainingService->isInCategory('testing'));
        $this->assertTrue($trainingService->requiresSkill('managerial'));
        $this->assertFalse($trainingService->requiresSkill('technical'));
    }
}
