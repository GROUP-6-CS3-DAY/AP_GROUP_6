<?php

namespace Tests\Feature\Application\UseCases;

use PHPUnit\Framework\TestCase;
use App\Application\UseCases\CreateFacilityUseCase;
use App\Application\UseCases\UpdateFacilityUseCase;
use App\Application\UseCases\DeleteFacilityUseCase;
use App\Application\DTOs\CreateFacilityDTO;
use App\Application\DTOs\UpdateFacilityDTO;
use App\Domain\Repositories\FacilityRepositoryInterface;
use App\Domain\Entities\Facility;
use DomainException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FacilityUseCasesTest extends TestCase
{
    use RefreshDatabase;

    private FacilityRepositoryInterface $facilityRepository;
    private CreateFacilityUseCase $createFacilityUseCase;
    private UpdateFacilityUseCase $updateFacilityUseCase;
    private DeleteFacilityUseCase $deleteFacilityUseCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->facilityRepository = app(FacilityRepositoryInterface::class);
        $this->createFacilityUseCase = app(CreateFacilityUseCase::class);
        $this->updateFacilityUseCase = app(UpdateFacilityUseCase::class);
        $this->deleteFacilityUseCase = app(DeleteFacilityUseCase::class);
    }

    public function test_can_create_facility()
    {
        $dto = new CreateFacilityDTO(
            name: 'Test Facility',
            location: 'Test Location',
            description: 'Test Description',
            partnerOrganization: 'Test Partner',
            facilityType: 'workshop',
            capabilities: ['cnc_machining', '3d_printing']
        );

        $facilityId = $this->createFacilityUseCase->execute($dto);

        $this->assertNotEmpty($facilityId);

        $facility = $this->facilityRepository->findById($facilityId);
        $this->assertInstanceOf(Facility::class, $facility);
        $this->assertEquals('Test Facility', $facility->getName());
        $this->assertEquals('Test Location', $facility->getLocation());
        $this->assertEquals('Test Description', $facility->getDescription());
        $this->assertEquals('Test Partner', $facility->getPartnerOrganization());
        $this->assertEquals('workshop', $facility->getFacilityType());
        $this->assertEquals(['cnc_machining', '3d_printing'], $facility->getCapabilities());
    }

    public function test_cannot_create_facility_with_duplicate_name_location()
    {
        // Create first facility
        $dto1 = new CreateFacilityDTO(
            name: 'Test Facility',
            location: 'Test Location',
            description: 'Test Description',
            partnerOrganization: 'Test Partner',
            facilityType: 'workshop',
            capabilities: ['cnc_machining']
        );

        $this->createFacilityUseCase->execute($dto1);

        // Try to create second facility with same name and location
        $dto2 = new CreateFacilityDTO(
            name: 'Test Facility',
            location: 'Test Location',
            description: 'Another Description',
            partnerOrganization: 'Another Partner',
            facilityType: 'laboratory',
            capabilities: ['3d_printing']
        );

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('A facility with this name already exists at this location');

        $this->createFacilityUseCase->execute($dto2);
    }

    public function test_can_update_facility()
    {
        // Create facility first
        $createDto = new CreateFacilityDTO(
            name: 'Test Facility',
            location: 'Test Location',
            description: 'Test Description',
            partnerOrganization: 'Test Partner',
            facilityType: 'workshop',
            capabilities: ['cnc_machining']
        );

        $facilityId = $this->createFacilityUseCase->execute($createDto);

        // Update facility
        $updateDto = new UpdateFacilityDTO(
            name: 'Updated Facility',
            description: 'Updated Description',
            capabilities: ['cnc_machining', '3d_printing']
        );

        $this->updateFacilityUseCase->execute($facilityId, $updateDto);

        $facility = $this->facilityRepository->findById($facilityId);
        $this->assertEquals('Updated Facility', $facility->getName());
        $this->assertEquals('Updated Description', $facility->getDescription());
        $this->assertEquals(['cnc_machining', '3d_printing'], $facility->getCapabilities());
        $this->assertEquals('Test Location', $facility->getLocation()); // Unchanged
    }

    public function test_cannot_update_facility_to_duplicate_name_location()
    {
        // Create first facility
        $dto1 = new CreateFacilityDTO(
            name: 'Test Facility',
            location: 'Test Location',
            description: 'Test Description',
            partnerOrganization: 'Test Partner',
            facilityType: 'workshop',
            capabilities: ['cnc_machining']
        );

        $facilityId1 = $this->createFacilityUseCase->execute($dto1);

        // Create second facility
        $dto2 = new CreateFacilityDTO(
            name: 'Another Facility',
            location: 'Another Location',
            description: 'Another Description',
            partnerOrganization: 'Another Partner',
            facilityType: 'laboratory',
            capabilities: ['3d_printing']
        );

        $facilityId2 = $this->createFacilityUseCase->execute($dto2);

        // Try to update second facility to have same name and location as first
        $updateDto = new UpdateFacilityDTO(
            name: 'Test Facility',
            location: 'Test Location'
        );

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('A facility with this name already exists at this location');

        $this->updateFacilityUseCase->execute($facilityId2, $updateDto);
    }

    public function test_can_delete_facility_without_dependencies()
    {
        // Create facility
        $dto = new CreateFacilityDTO(
            name: 'Test Facility',
            location: 'Test Location',
            description: 'Test Description',
            partnerOrganization: 'Test Partner',
            facilityType: 'workshop',
            capabilities: ['cnc_machining']
        );

        $facilityId = $this->createFacilityUseCase->execute($dto);

        // Verify facility exists
        $facility = $this->facilityRepository->findById($facilityId);
        $this->assertInstanceOf(Facility::class, $facility);

        // Delete facility
        $this->deleteFacilityUseCase->execute($facilityId);

        // Verify facility is deleted
        $deletedFacility = $this->facilityRepository->findById($facilityId);
        $this->assertNull($deletedFacility);
    }

    public function test_cannot_delete_facility_with_dependencies()
    {
        // Create facility
        $dto = new CreateFacilityDTO(
            name: 'Test Facility',
            location: 'Test Location',
            description: 'Test Description',
            partnerOrganization: 'Test Partner',
            facilityType: 'workshop',
            capabilities: ['cnc_machining']
        );

        $facilityId = $this->createFacilityUseCase->execute($dto);

        // Add dependencies (simulate having services/equipment/projects)
        $facility = $this->facilityRepository->findById($facilityId);
        $facility->addService('service-1');
        $this->facilityRepository->save($facility);

        // Try to delete facility with dependencies
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Facility has dependent records (Services/Equipment/Projects)');

        $this->deleteFacilityUseCase->execute($facilityId);
    }

    public function test_throws_exception_when_updating_nonexistent_facility()
    {
        $updateDto = new UpdateFacilityDTO(
            name: 'Updated Facility'
        );

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Facility not found');

        $this->updateFacilityUseCase->execute('nonexistent-id', $updateDto);
    }

    public function test_throws_exception_when_deleting_nonexistent_facility()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Facility not found');

        $this->deleteFacilityUseCase->execute('nonexistent-id');
    }
}
