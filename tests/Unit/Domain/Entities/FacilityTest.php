<?php

namespace Tests\Unit\Domain\Entities;

use PHPUnit\Framework\TestCase;
use App\Domain\Entities\Facility;
use DomainException;

class FacilityTest extends TestCase
{
    public function test_can_create_facility_with_valid_data()
    {
        $facility = new Facility(
            id: 'facility-1',
            name: 'Test Facility',
            location: 'Test Location',
            description: 'Test Description',
            partnerOrganization: 'Test Partner',
            facilityType: 'workshop',
            capabilities: ['cnc_machining', '3d_printing']
        );

        $this->assertEquals('facility-1', $facility->getId());
        $this->assertEquals('Test Facility', $facility->getName());
        $this->assertEquals('Test Location', $facility->getLocation());
        $this->assertEquals('Test Description', $facility->getDescription());
        $this->assertEquals('Test Partner', $facility->getPartnerOrganization());
        $this->assertEquals('workshop', $facility->getFacilityType());
        $this->assertEquals(['cnc_machining', '3d_printing'], $facility->getCapabilities());
    }

    public function test_throws_exception_when_name_is_empty()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Facility.Name is required');

        new Facility(
            id: 'facility-1',
            name: '',
            location: 'Test Location',
            description: 'Test Description',
            partnerOrganization: 'Test Partner',
            facilityType: 'workshop',
            capabilities: ['cnc_machining']
        );
    }

    public function test_throws_exception_when_location_is_empty()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Facility.Location is required');

        new Facility(
            id: 'facility-1',
            name: 'Test Facility',
            location: '',
            description: 'Test Description',
            partnerOrganization: 'Test Partner',
            facilityType: 'workshop',
            capabilities: ['cnc_machining']
        );
    }

    public function test_throws_exception_when_facility_type_is_empty()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Facility.FacilityType is required');

        new Facility(
            id: 'facility-1',
            name: 'Test Facility',
            location: 'Test Location',
            description: 'Test Description',
            partnerOrganization: 'Test Partner',
            facilityType: '',
            capabilities: ['cnc_machining']
        );
    }

    public function test_throws_exception_when_capabilities_required_but_empty()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Facility.Capabilities must be populated when Services/Equipment exist');

        new Facility(
            id: 'facility-1',
            name: 'Test Facility',
            location: 'Test Location',
            description: 'Test Description',
            partnerOrganization: 'Test Partner',
            facilityType: 'workshop',
            capabilities: [],
            services: ['service-1'],
            equipment: []
        );
    }

    public function test_throws_exception_for_invalid_facility_type()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Invalid facility type');

        new Facility(
            id: 'facility-1',
            name: 'Test Facility',
            location: 'Test Location',
            description: 'Test Description',
            partnerOrganization: 'Test Partner',
            facilityType: 'invalid_type',
            capabilities: ['cnc_machining']
        );
    }

    public function test_throws_exception_for_invalid_capability()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Invalid capability: invalid_capability');

        new Facility(
            id: 'facility-1',
            name: 'Test Facility',
            location: 'Test Location',
            description: 'Test Description',
            partnerOrganization: 'Test Partner',
            facilityType: 'workshop',
            capabilities: ['invalid_capability']
        );
    }

    public function test_throws_exception_for_duplicate_name_location()
    {
        $existingFacilities = [
            ['id' => 'facility-2', 'name' => 'Test Facility', 'location' => 'Test Location']
        ];

        $facility = new Facility(
            id: 'facility-1',
            name: 'Test Facility',
            location: 'Test Location',
            description: 'Test Description',
            partnerOrganization: 'Test Partner',
            facilityType: 'workshop',
            capabilities: ['cnc_machining']
        );

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('A facility with this name already exists at this location');

        $facility->validateNameLocationUniqueness($existingFacilities);
    }

    public function test_can_be_deleted_when_no_dependencies()
    {
        $facility = new Facility(
            id: 'facility-1',
            name: 'Test Facility',
            location: 'Test Location',
            description: 'Test Description',
            partnerOrganization: 'Test Partner',
            facilityType: 'workshop',
            capabilities: ['cnc_machining'],
            services: [],
            equipment: [],
            projects: []
        );

        $this->assertTrue($facility->canBeDeleted());
    }

    public function test_cannot_be_deleted_when_has_dependencies()
    {
        $facility = new Facility(
            id: 'facility-1',
            name: 'Test Facility',
            location: 'Test Location',
            description: 'Test Description',
            partnerOrganization: 'Test Partner',
            facilityType: 'workshop',
            capabilities: ['cnc_machining'],
            services: ['service-1'],
            equipment: [],
            projects: []
        );

        $this->assertFalse($facility->canBeDeleted());

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Facility has dependent records (Services/Equipment/Projects)');

        $facility->validateDeletion();
    }

    public function test_can_update_facility_data()
    {
        $facility = new Facility(
            id: 'facility-1',
            name: 'Test Facility',
            location: 'Test Location',
            description: 'Test Description',
            partnerOrganization: 'Test Partner',
            facilityType: 'workshop',
            capabilities: ['cnc_machining']
        );

        $facility->update([
            'name' => 'Updated Facility',
            'description' => 'Updated Description'
        ]);

        $this->assertEquals('Updated Facility', $facility->getName());
        $this->assertEquals('Updated Description', $facility->getDescription());
        $this->assertEquals('Test Location', $facility->getLocation()); // Unchanged
    }

    public function test_can_add_and_remove_services()
    {
        $facility = new Facility(
            id: 'facility-1',
            name: 'Test Facility',
            location: 'Test Location',
            description: 'Test Description',
            partnerOrganization: 'Test Partner',
            facilityType: 'workshop',
            capabilities: ['cnc_machining']
        );

        $facility->addService('service-1');
        $facility->addService('service-2');

        $this->assertEquals(['service-1', 'service-2'], $facility->getServices());
        $this->assertEquals(2, $facility->getServiceCount());

        $facility->removeService('service-1');
        $this->assertEquals([1 => 'service-2'], $facility->getServices());
        $this->assertEquals(1, $facility->getServiceCount());
    }

    public function test_can_check_capability()
    {
        $facility = new Facility(
            id: 'facility-1',
            name: 'Test Facility',
            location: 'Test Location',
            description: 'Test Description',
            partnerOrganization: 'Test Partner',
            facilityType: 'workshop',
            capabilities: ['cnc_machining', '3d_printing']
        );

        $this->assertTrue($facility->hasCapability('cnc_machining'));
        $this->assertTrue($facility->hasCapability('3d_printing'));
        $this->assertFalse($facility->hasCapability('laser_cutting'));
    }

    public function test_is_operational_when_has_capabilities_and_services()
    {
        $facility = new Facility(
            id: 'facility-1',
            name: 'Test Facility',
            location: 'Test Location',
            description: 'Test Description',
            partnerOrganization: 'Test Partner',
            facilityType: 'workshop',
            capabilities: ['cnc_machining'],
            services: ['service-1']
        );

        $this->assertTrue($facility->isOperational());
        $this->assertTrue($facility->canHostProjects());
    }
}
