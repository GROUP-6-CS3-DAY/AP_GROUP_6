<?php

namespace Tests\Unit\Domain\Entities;

use PHPUnit\Framework\TestCase;
use App\Domain\Entities\Equipment;
use App\Domain\ValueObjects\UsageDomain;
use App\Domain\ValueObjects\SupportPhase;

class EquipmentTest extends TestCase
{
    public function test_can_create_equipment_with_valid_data()
    {
        $equipment = new Equipment(
            id: 'equip-123',
            facilityId: 'fac-1',
            name: 'CNC Machine XZ100',
            capabilities: ['cnc_machining', 'precision_cutting'],
            description: 'High precision CNC machining equipment',
            inventoryCode: 'CNC-001',
            usageDomain: new UsageDomain('mechanical'),
            supportPhase: new SupportPhase('prototyping')
        );

        $this->assertEquals('equip-123', $equipment->getId());
        $this->assertEquals('fac-1', $equipment->getFacilityId());
        $this->assertEquals('CNC Machine XZ100', $equipment->getName());
        $this->assertEquals('CNC-001', $equipment->getInventoryCode());
        $this->assertEquals('mechanical', $equipment->getUsageDomain()->getValue());
        $this->assertEquals('prototyping', $equipment->getSupportPhase()->getValue());
    }

    public function test_required_fields_validation_facility_id()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Equipment.FacilityId, Equipment.Name, and Equipment.InventoryCode are required');

        new Equipment(
            id: 'equip-123',
            facilityId: '', // Empty facility ID should fail
            name: 'Valid Equipment Name',
            capabilities: ['cnc_machining'],
            description: 'Valid description',
            inventoryCode: 'CNC-001',
            usageDomain: new UsageDomain('mechanical'),
            supportPhase: new SupportPhase('prototyping')
        );
    }

    public function test_required_fields_validation_name()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Equipment.FacilityId, Equipment.Name, and Equipment.InventoryCode are required');

        new Equipment(
            id: 'equip-123',
            facilityId: 'fac-1',
            name: '', // Empty name should fail
            capabilities: ['cnc_machining'],
            description: 'Valid description',
            inventoryCode: 'CNC-001',
            usageDomain: new UsageDomain('mechanical'),
            supportPhase: new SupportPhase('prototyping')
        );
    }

    public function test_required_fields_validation_inventory_code()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Equipment.FacilityId, Equipment.Name, and Equipment.InventoryCode are required');

        new Equipment(
            id: 'equip-123',
            facilityId: 'fac-1',
            name: 'Valid Equipment Name',
            capabilities: ['cnc_machining'],
            description: 'Valid description',
            inventoryCode: '', // Empty inventory code should fail
            usageDomain: new UsageDomain('mechanical'),
            supportPhase: new SupportPhase('prototyping')
        );
    }

    public function test_inventory_code_uniqueness_validation()
    {
        $equipment = new Equipment(
            id: 'equip-123',
            facilityId: 'fac-1',
            name: 'CNC Machine XZ100',
            capabilities: ['cnc_machining'],
            description: 'High precision CNC machining equipment',
            inventoryCode: 'CNC-001',
            usageDomain: new UsageDomain('mechanical'),
            supportPhase: new SupportPhase('prototyping')
        );

        $existingInventoryCodes = ['cnc-001', 'PCB-002', 'TEST-003']; // Case insensitive

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Equipment.InventoryCode already exists');

        $equipment->validateInventoryCodeUniqueness($existingInventoryCodes);
    }

    public function test_electronics_equipment_must_support_prototyping_or_testing()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Electronics equipment must support Prototyping or Testing');

        new Equipment(
            id: 'equip-123',
            facilityId: 'fac-1',
            name: 'Electronics Test Board',
            capabilities: ['electronics_testing'],
            description: 'Electronics testing equipment',
            inventoryCode: 'ELEC-001',
            usageDomain: new UsageDomain('electronics'), // Electronics domain
            supportPhase: new SupportPhase('training') // Training only - should fail
        );
    }

    public function test_electronics_equipment_can_support_prototyping()
    {
        $equipment = new Equipment(
            id: 'equip-123',
            facilityId: 'fac-1',
            name: 'Electronics Test Board',
            capabilities: ['electronics_testing'],
            description: 'Electronics testing equipment',
            inventoryCode: 'ELEC-001',
            usageDomain: new UsageDomain('electronics'),
            supportPhase: new SupportPhase('prototyping') // Valid for electronics
        );

        $this->assertTrue($equipment->canSupportElectronicsWork());
    }

    public function test_electronics_equipment_can_support_testing()
    {
        $equipment = new Equipment(
            id: 'equip-123',
            facilityId: 'fac-1',
            name: 'Electronics Test Board',
            capabilities: ['electronics_testing'],
            description: 'Electronics testing equipment',
            inventoryCode: 'ELEC-001',
            usageDomain: new UsageDomain('electronics'),
            supportPhase: new SupportPhase('testing') // Valid for electronics
        );

        $this->assertTrue($equipment->canSupportElectronicsWork());
    }

    public function test_non_electronics_equipment_can_use_any_support_phase()
    {
        // Mechanical equipment with training phase should be allowed
        $equipment = new Equipment(
            id: 'equip-123',
            facilityId: 'fac-1',
            name: 'Mechanical Training Tool',
            capabilities: ['basic_machining'],
            description: 'Training equipment for mechanical work',
            inventoryCode: 'MECH-001',
            usageDomain: new UsageDomain('mechanical'),
            supportPhase: new SupportPhase('training') // Valid for non-electronics
        );

        $this->assertNotNull($equipment);
        $this->assertFalse($equipment->canSupportElectronicsWork()); // Not electronics equipment
    }

    public function test_equipment_deletion_safety_with_active_projects()
    {
        $equipment = new Equipment(
            id: 'equip-123',
            facilityId: 'fac-1',
            name: 'CNC Machine XZ100',
            capabilities: ['cnc_machining'],
            description: 'CNC machining equipment',
            inventoryCode: 'CNC-001',
            usageDomain: new UsageDomain('mechanical'),
            supportPhase: new SupportPhase('prototyping')
        );

        $activeProjectsInFacility = [
            [
                'id' => 'proj-1',
                'status' => 'active',
                'equipment_ids' => ['equip-123'], // References this equipment
                'technical_requirements' => []
            ]
        ];

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Equipment referenced by active Project');

        $equipment->validateDeletionSafety($activeProjectsInFacility);
    }

    public function test_equipment_deletion_safety_with_technical_requirements_reference()
    {
        $equipment = new Equipment(
            id: 'equip-123',
            facilityId: 'fac-1',
            name: 'CNC Machine XZ100',
            capabilities: ['cnc_machining'],
            description: 'CNC machining equipment',
            inventoryCode: 'CNC-001',
            usageDomain: new UsageDomain('mechanical'),
            supportPhase: new SupportPhase('prototyping')
        );

        $activeProjectsInFacility = [
            [
                'id' => 'proj-1',
                'status' => 'active',
                'equipment_ids' => [],
                'technical_requirements' => ['CNC-001'] // References by inventory code
            ]
        ];

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Equipment referenced by active Project');

        $equipment->validateDeletionSafety($activeProjectsInFacility);
    }

    public function test_equipment_can_be_deleted_when_no_active_project_references()
    {
        $equipment = new Equipment(
            id: 'equip-123',
            facilityId: 'fac-1',
            name: 'CNC Machine XZ100',
            capabilities: ['cnc_machining'],
            description: 'CNC machining equipment',
            inventoryCode: 'CNC-001',
            usageDomain: new UsageDomain('mechanical'),
            supportPhase: new SupportPhase('prototyping')
        );

        $activeProjectsInFacility = [
            [
                'id' => 'proj-1',
                'status' => 'active',
                'equipment_ids' => ['equip-456'], // Different equipment
                'technical_requirements' => ['OTHER-002'] // Different inventory code
            ]
        ];

        // Should not throw exception
        $equipment->validateDeletionSafety($activeProjectsInFacility);
        $this->assertTrue(true); // Test passes if no exception is thrown
    }

    public function test_update_validates_usage_domain_support_phase_coherence()
    {
        $equipment = new Equipment(
            id: 'equip-123',
            facilityId: 'fac-1',
            name: 'Mechanical Tool',
            capabilities: ['machining'],
            description: 'Mechanical equipment',
            inventoryCode: 'MECH-001',
            usageDomain: new UsageDomain('mechanical'),
            supportPhase: new SupportPhase('training')
        );

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Electronics equipment must support Prototyping or Testing');

        // Try to update to electronics with training phase - should fail
        $equipment->update([
            'usage_domain' => 'electronics',
            'support_phase' => 'training'
        ]);
    }

    public function test_equipment_business_logic_methods()
    {
        $equipment = new Equipment(
            id: 'equip-123',
            facilityId: 'fac-1',
            name: 'Multi-Purpose Equipment',
            capabilities: ['machining', 'testing', 'prototyping'],
            description: 'Versatile equipment for multiple uses',
            inventoryCode: 'MULTI-001',
            usageDomain: new UsageDomain('mechanical'),
            supportPhase: new SupportPhase('prototyping')
        );

        $this->assertTrue($equipment->hasCapability('machining'));
        $this->assertFalse($equipment->hasCapability('welding'));
        $this->assertEquals('machining, testing, prototyping', $equipment->getCapabilitiesAsString());
        $this->assertTrue($equipment->isAvailableForPhase('testing'));
    }
}
