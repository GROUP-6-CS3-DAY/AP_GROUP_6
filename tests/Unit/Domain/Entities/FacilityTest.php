<?php

namespace Tests\Unit\Domain\Entities;

use PHPUnit\Framework\TestCase;
use App\Domain\Entities\Facility;
use App\Domain\ValueObjects\FacilityType;

class FacilityTest extends TestCase
{
    public function test_can_create_facility_with_valid_data()
    {
        $facilityType = new FacilityType('workshop');
        
        $facility = new Facility(
            id: 'facility-1',
            name: 'Test Facility',
            description: 'Test Description',
            location: 'Test Location',
            facilityType: $facilityType,
            capacity: 50,
            equipmentList: ['CNC Machine', '3D Printer'],
            capabilities: ['cnc_machining', '3d_printing'],
            availabilityStatus: 'available'
        );

        $this->assertEquals('facility-1', $facility->getId());
        $this->assertEquals('Test Facility', $facility->getName());
        $this->assertEquals('Test Location', $facility->getLocation());
        $this->assertEquals('Test Description', $facility->getDescription());
        $this->assertEquals('workshop', $facility->getFacilityType()->getValue());
        $this->assertEquals(50, $facility->getCapacity());
        $this->assertEquals(['CNC Machine', '3D Printer'], $facility->getEquipmentList());
        $this->assertEquals(['cnc_machining', '3d_printing'], $facility->getCapabilities());
        $this->assertEquals('available', $facility->getAvailabilityStatus());
    }

    public function test_throws_exception_when_name_is_empty()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Facility.Name is required');

        new Facility(
            id: 'facility-1',
            name: '',
            description: 'Test Description',
            location: 'Test Location',
            facilityType: new FacilityType('workshop')
        );
    }

    public function test_throws_exception_when_location_is_empty()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Facility.Location is required');

        new Facility(
            id: 'facility-1',
            name: 'Test Facility',
            description: 'Test Description',
            location: '',
            facilityType: new FacilityType('workshop')
        );
    }

    public function test_throws_exception_when_capabilities_required_but_empty()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Facility.Capabilities must be populated when Equipment exist');

        new Facility(
            id: 'facility-1',
            name: 'Test Facility',
            description: 'Test Description',
            location: 'Test Location',
            facilityType: new FacilityType('workshop'),
            capacity: 50,
            equipmentList: ['CNC Machine'], // Has equipment
            capabilities: [] // But no capabilities
        );
    }

    public function test_throws_exception_for_invalid_facility_type()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid facility type: invalid_type');

        new FacilityType('invalid_type');
    }

    public function test_can_update_facility_data()
    {
        $facility = new Facility(
            id: 'facility-1',
            name: 'Test Facility',
            description: 'Test Description',
            location: 'Test Location',
            facilityType: new FacilityType('workshop'),
            capacity: 50,
            equipmentList: ['CNC Machine'],
            capabilities: ['cnc_machining']
        );

        $facility->update([
            'name' => 'Updated Facility',
            'description' => 'Updated Description',
            'capacity' => 100
        ]);

        $this->assertEquals('Updated Facility', $facility->getName());
        $this->assertEquals('Updated Description', $facility->getDescription());
        $this->assertEquals(100, $facility->getCapacity());
        $this->assertEquals('Test Location', $facility->getLocation()); // Unchanged
    }

    public function test_can_check_capability()
    {
        $facility = new Facility(
            id: 'facility-1',
            name: 'Test Facility',
            description: 'Test Description',
            location: 'Test Location',
            facilityType: new FacilityType('workshop'),
            capabilities: ['cnc_machining', '3d_printing']
        );

        $this->assertTrue($facility->hasCapability('cnc_machining'));
        $this->assertTrue($facility->hasCapability('3d_printing'));
        $this->assertFalse($facility->hasCapability('laser_cutting'));
    }

    public function test_facility_business_logic_methods()
    {
        $facility = new Facility(
            id: 'facility-1',
            name: 'Test Facility',
            description: 'Test Description',
            location: 'Test Location',
            facilityType: new FacilityType('workshop'),
            capacity: 100,
            equipmentList: ['CNC Machine', '3D Printer'],
            capabilities: ['cnc_machining', '3d_printing'],
            availabilityStatus: 'available'
        );

        $this->assertTrue($facility->hasEquipment());
        $this->assertTrue($facility->hasCapabilities());
        $this->assertTrue($facility->isAvailable());
        $this->assertTrue($facility->canAccommodate(50)); // Less than capacity
        $this->assertFalse($facility->canAccommodate(150)); // More than capacity
        $this->assertEquals('test facility|test location', $facility->getLocationIdentifier());
    }

    public function test_facility_unavailable_cannot_accommodate()
    {
        $facility = new Facility(
            id: 'facility-1',
            name: 'Test Facility',
            description: 'Test Description',
            location: 'Test Location',
            facilityType: new FacilityType('workshop'),
            capacity: 100,
            availabilityStatus: 'maintenance'
        );

        $this->assertFalse($facility->isAvailable());
        $this->assertFalse($facility->canAccommodate(50)); // Even though capacity allows, status doesn't
    }

    public function test_facility_without_equipment_or_capabilities()
    {
        $facility = new Facility(
            id: 'facility-1',
            name: 'Test Facility',
            description: 'Test Description',
            location: 'Test Location',
            facilityType: new FacilityType('office'),
            equipmentList: [],
            capabilities: []
        );

        $this->assertFalse($facility->hasEquipment());
        $this->assertFalse($facility->hasCapabilities());
    }
}
