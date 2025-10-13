<?php

namespace Tests\Feature\Application\UseCases;

use Tests\TestCase;
use App\Application\UseCases\CreateServiceUseCase;
use App\Application\UseCases\UpdateServiceUseCase;
use App\Application\UseCases\DeleteServiceUseCase;
use App\Application\DTOs\CreateServiceDTO;
use App\Application\DTOs\UpdateServiceDTO;
use App\Domain\Repositories\ServiceRepositoryInterface;
use App\Domain\Repositories\FacilityRepositoryInterface;
use App\Domain\Entities\Service;
use App\Domain\Entities\Facility;
use DomainException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ServiceUseCasesTest extends TestCase
{
    use RefreshDatabase;

    private ServiceRepositoryInterface $serviceRepository;
    private FacilityRepositoryInterface $facilityRepository;
    private CreateServiceUseCase $createServiceUseCase;
    private UpdateServiceUseCase $updateServiceUseCase;
    private DeleteServiceUseCase $deleteServiceUseCase;
    private string $facilityId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->serviceRepository = app(ServiceRepositoryInterface::class);
        $this->facilityRepository = app(FacilityRepositoryInterface::class);
        $this->createServiceUseCase = app(CreateServiceUseCase::class);
        $this->updateServiceUseCase = app(UpdateServiceUseCase::class);
        $this->deleteServiceUseCase = app(DeleteServiceUseCase::class);

        // Create a test facility
        $this->facilityId = $this->createTestFacility();
    }

    private function createTestFacility(): string
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

        $this->facilityRepository->save($facility);
        return $facility->getId();
    }

    public function test_can_create_service()
    {
        $dto = new CreateServiceDTO(
            facilityId: $this->facilityId,
            name: 'Test Service',
            description: 'Test Description',
            category: 'testing',
            skillType: 'hardware'
        );

        $serviceId = $this->createServiceUseCase->execute($dto);

        $this->assertNotEmpty($serviceId);

        $service = $this->serviceRepository->findById($serviceId);
        $this->assertInstanceOf(Service::class, $service);
        $this->assertEquals($this->facilityId, $service->getFacilityId());
        $this->assertEquals('Test Service', $service->getName());
        $this->assertEquals('Test Description', $service->getDescription());
        $this->assertEquals('testing', $service->getCategory());
        $this->assertEquals('hardware', $service->getSkillType());
    }

    public function test_cannot_create_service_with_nonexistent_facility()
    {
        $dto = new CreateServiceDTO(
            facilityId: 'nonexistent-facility-id',
            name: 'Test Service',
            description: 'Test Description',
            category: 'testing',
            skillType: 'hardware'
        );

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Facility not found');

        $this->createServiceUseCase->execute($dto);
    }

    public function test_cannot_create_service_with_duplicate_name_in_facility()
    {
        // Create first service
        $dto1 = new CreateServiceDTO(
            facilityId: $this->facilityId,
            name: 'Test Service',
            description: 'Test Description',
            category: 'testing',
            skillType: 'hardware'
        );

        $this->createServiceUseCase->execute($dto1);

        // Try to create second service with same name in same facility
        $dto2 = new CreateServiceDTO(
            facilityId: $this->facilityId,
            name: 'Test Service',
            description: 'Another Description',
            category: 'training',
            skillType: 'software'
        );

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('A service with this name already exists in this facility');

        $this->createServiceUseCase->execute($dto2);
    }

    public function test_can_create_services_with_same_name_in_different_facilities()
    {
        // Create second facility
        $facility2 = new Facility(
            id: 'facility-2',
            name: 'Test Facility 2',
            location: 'Test Location 2',
            description: 'Test Description 2',
            partnerOrganization: 'Test Partner 2',
            facilityType: 'laboratory',
            capabilities: ['analysis']
        );

        $this->facilityRepository->save($facility2);

        // Create service in first facility
        $dto1 = new CreateServiceDTO(
            facilityId: $this->facilityId,
            name: 'Test Service',
            description: 'Test Description',
            category: 'testing',
            skillType: 'hardware'
        );

        $serviceId1 = $this->createServiceUseCase->execute($dto1);

        // Create service with same name in second facility
        $dto2 = new CreateServiceDTO(
            facilityId: $facility2->getId(),
            name: 'Test Service',
            description: 'Another Description',
            category: 'training',
            skillType: 'software'
        );

        $serviceId2 = $this->createServiceUseCase->execute($dto2);

        $this->assertNotEquals($serviceId1, $serviceId2);

        $service1 = $this->serviceRepository->findById($serviceId1);
        $service2 = $this->serviceRepository->findById($serviceId2);

        $this->assertEquals('Test Service', $service1->getName());
        $this->assertEquals('Test Service', $service2->getName());
        $this->assertEquals($this->facilityId, $service1->getFacilityId());
        $this->assertEquals($facility2->getId(), $service2->getFacilityId());
    }

    public function test_can_update_service()
    {
        // Create service first
        $createDto = new CreateServiceDTO(
            facilityId: $this->facilityId,
            name: 'Test Service',
            description: 'Test Description',
            category: 'testing',
            skillType: 'hardware'
        );

        $serviceId = $this->createServiceUseCase->execute($createDto);

        // Update service
        $updateDto = new UpdateServiceDTO(
            name: 'Updated Service',
            description: 'Updated Description',
            category: 'training'
        );

        $this->updateServiceUseCase->execute($serviceId, $updateDto);

        $service = $this->serviceRepository->findById($serviceId);
        $this->assertEquals('Updated Service', $service->getName());
        $this->assertEquals('Updated Description', $service->getDescription());
        $this->assertEquals('training', $service->getCategory());
        $this->assertEquals('hardware', $service->getSkillType()); // Unchanged
    }

    public function test_can_delete_service_without_active_projects()
    {
        // Create service
        $dto = new CreateServiceDTO(
            facilityId: $this->facilityId,
            name: 'Test Service',
            description: 'Test Description',
            category: 'testing',
            skillType: 'hardware'
        );

        $serviceId = $this->createServiceUseCase->execute($dto);

        // Verify service exists
        $service = $this->serviceRepository->findById($serviceId);
        $this->assertInstanceOf(Service::class, $service);

        // Delete service
        $this->deleteServiceUseCase->execute($serviceId);

        // Verify service is deleted
        $deletedService = $this->serviceRepository->findById($serviceId);
        $this->assertNull($deletedService);
    }

    public function test_cannot_delete_service_with_active_projects()
    {
        // Create service
        $dto = new CreateServiceDTO(
            facilityId: $this->facilityId,
            name: 'Test Service',
            description: 'Test Description',
            category: 'testing',
            skillType: 'hardware'
        );

        $serviceId = $this->createServiceUseCase->execute($dto);

        // Mock active projects using this service category
        $this->mock(\App\Domain\Repositories\ServiceRepositoryInterface::class)
            ->shouldReceive('findActiveProjectsUsingServiceCategory')
            ->with($this->facilityId, 'testing')
            ->andReturn(['project-1']);

        // Try to delete service with active projects
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Service in use by Project testing requirements');

        $this->deleteServiceUseCase->execute($serviceId);
    }

    public function test_throws_exception_when_updating_nonexistent_service()
    {
        $updateDto = new UpdateServiceDTO(
            name: 'Updated Service'
        );

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Service not found');

        $this->updateServiceUseCase->execute('nonexistent-id', $updateDto);
    }

    public function test_throws_exception_when_deleting_nonexistent_service()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Service not found');

        $this->deleteServiceUseCase->execute('nonexistent-id');
    }

    public function test_throws_exception_when_updating_to_nonexistent_facility()
    {
        // Create service first
        $createDto = new CreateServiceDTO(
            facilityId: $this->facilityId,
            name: 'Test Service',
            description: 'Test Description',
            category: 'testing',
            skillType: 'hardware'
        );

        $serviceId = $this->createServiceUseCase->execute($createDto);

        // Try to update to nonexistent facility
        $updateDto = new UpdateServiceDTO(
            facilityId: 'nonexistent-facility-id'
        );

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Facility not found');

        $this->updateServiceUseCase->execute($serviceId, $updateDto);
    }
}
