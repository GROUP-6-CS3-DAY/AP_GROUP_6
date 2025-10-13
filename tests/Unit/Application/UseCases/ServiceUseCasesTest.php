<?php

namespace Tests\Unit\Application\UseCases;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Application\UseCases\CreateServiceUseCase;
use App\Application\UseCases\UpdateServiceUseCase;
use App\Application\UseCases\DeleteServiceUseCase;
use App\Application\DTOs\CreateServiceDTO;
use App\Application\DTOs\UpdateServiceDTO;
use App\Domain\Repositories\ServiceRepositoryInterface;
use App\Domain\Repositories\FacilityRepositoryInterface;
use App\Domain\Entities\Service;
use App\Domain\Entities\Facility;
use App\Application\Exceptions\ServiceNotFoundException;
use App\Application\Exceptions\FacilityNotFoundException;

class ServiceUseCasesTest extends TestCase
{
    private ServiceRepositoryInterface|MockObject $mockServiceRepository;
    private FacilityRepositoryInterface|MockObject $mockFacilityRepository;
    private CreateServiceUseCase $createUseCase;
    private UpdateServiceUseCase $updateUseCase;
    private DeleteServiceUseCase $deleteUseCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockServiceRepository = $this->createMock(ServiceRepositoryInterface::class);
        $this->mockFacilityRepository = $this->createMock(FacilityRepositoryInterface::class);
        
        $this->createUseCase = new CreateServiceUseCase(
            $this->mockServiceRepository,
            $this->mockFacilityRepository
        );
        $this->updateUseCase = new UpdateServiceUseCase($this->mockServiceRepository);
        $this->deleteUseCase = new DeleteServiceUseCase($this->mockServiceRepository);
    }

    public function test_can_create_service()
    {
        $dto = new CreateServiceDTO(
            facilityId: 'facility-1',
            name: 'Test Service',
            description: 'Test Description',
            category: 'testing',
            skillType: 'technical',
            requirements: ['requirement1'],
            availabilityStatus: 'available',
            cost: 100.50
        );

        $mockFacility = $this->createMock(Facility::class);
        $this->mockFacilityRepository
            ->expects($this->once())
            ->method('findById')
            ->with('facility-1')
            ->willReturn($mockFacility);

        $this->mockServiceRepository
            ->expects($this->once())
            ->method('findByNameAndFacility')
            ->with('Test Service', 'facility-1')
            ->willReturn(null);

        $this->mockServiceRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Service $service) {
                return $service->getFacilityId() === 'facility-1' &&
                       $service->getName() === 'Test Service' &&
                       $service->getCategory()->getValue() === 'testing';
            }));

        $serviceId = $this->createUseCase->execute($dto);

        $this->assertIsString($serviceId);
        $this->assertNotEmpty($serviceId);
    }

    public function test_cannot_create_service_with_nonexistent_facility()
    {
        $dto = new CreateServiceDTO(
            facilityId: 'nonexistent-facility',
            name: 'Test Service',
            description: 'Test Description',
            category: 'testing',
            skillType: 'technical',
            requirements: [],
            availabilityStatus: 'available',
            cost: 0.0
        );

        $this->mockFacilityRepository
            ->expects($this->once())
            ->method('findById')
            ->with('nonexistent-facility')
            ->willReturn(null);

        $this->mockServiceRepository
            ->expects($this->never())
            ->method('save');

        $this->expectException(FacilityNotFoundException::class);
        $this->expectExceptionMessage('Facility with ID nonexistent-facility not found');

        $this->createUseCase->execute($dto);
    }

    public function test_cannot_create_service_with_duplicate_name_in_facility()
    {
        $dto = new CreateServiceDTO(
            facilityId: 'facility-1',
            name: 'Test Service',
            description: 'Test Description',
            category: 'testing',
            skillType: 'technical',
            requirements: [],
            availabilityStatus: 'available',
            cost: 0.0
        );

        $mockFacility = $this->createMock(Facility::class);
        $mockExistingService = $this->createMock(Service::class);

        $this->mockFacilityRepository
            ->expects($this->once())
            ->method('findById')
            ->with('facility-1')
            ->willReturn($mockFacility);

        $this->mockServiceRepository
            ->expects($this->once())
            ->method('findByNameAndFacility')
            ->with('Test Service', 'facility-1')
            ->willReturn($mockExistingService);

        $this->mockServiceRepository
            ->expects($this->never())
            ->method('save');

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('A service with this name already exists in this facility');

        $this->createUseCase->execute($dto);
    }

    public function test_can_update_service()
    {
        $serviceId = 'service-123';
        
        $mockService = $this->createMock(Service::class);
        $mockService->method('getFacilityId')->willReturn('facility-1');
        $mockService->expects($this->once())
            ->method('update')
            ->with([
                'name' => 'Updated Service',
                'description' => 'Updated Description',
                'cost' => 200.00
            ]);

        $this->mockServiceRepository
            ->expects($this->once())
            ->method('findById')
            ->with($serviceId)
            ->willReturn($mockService);

        $this->mockServiceRepository
            ->expects($this->once())
            ->method('findByNameAndFacility')
            ->with('Updated Service', 'facility-1')
            ->willReturn(null);

        $this->mockServiceRepository
            ->expects($this->once())
            ->method('save')
            ->with($mockService);

        $updateDto = new UpdateServiceDTO(
            id: $serviceId,
            name: 'Updated Service',
            description: 'Updated Description',
            cost: 200.00
        );

        $this->updateUseCase->execute($updateDto);
    }

    public function test_throws_exception_when_updating_nonexistent_service()
    {
        $serviceId = 'nonexistent-id';
        
        $this->mockServiceRepository
            ->expects($this->once())
            ->method('findById')
            ->with($serviceId)
            ->willReturn(null);

        $this->mockServiceRepository
            ->expects($this->never())
            ->method('save');

        $updateDto = new UpdateServiceDTO(
            id: $serviceId,
            name: 'Updated Service'
        );

        $this->expectException(ServiceNotFoundException::class);
        $this->expectExceptionMessage("Service with ID {$serviceId} not found");

        $this->updateUseCase->execute($updateDto);
    }

    public function test_can_delete_service_without_project_references()
    {
        $serviceId = 'service-123';
        
        $mockService = $this->createMock(Service::class);

        $this->mockServiceRepository
            ->expects($this->once())
            ->method('findById')
            ->with($serviceId)
            ->willReturn($mockService);

        $this->mockServiceRepository
            ->expects($this->once())
            ->method('isReferencedByProjectTestingRequirements')
            ->with($serviceId)
            ->willReturn(false);

        $this->mockServiceRepository
            ->expects($this->once())
            ->method('delete')
            ->with($serviceId);

        $this->deleteUseCase->execute($serviceId);
    }

    public function test_cannot_delete_service_with_project_references()
    {
        $serviceId = 'service-123';
        
        $mockService = $this->createMock(Service::class);

        $this->mockServiceRepository
            ->expects($this->once())
            ->method('findById')
            ->with($serviceId)
            ->willReturn($mockService);

        $this->mockServiceRepository
            ->expects($this->once())
            ->method('isReferencedByProjectTestingRequirements')
            ->with($serviceId)
            ->willReturn(true);

        $this->mockServiceRepository
            ->expects($this->never())
            ->method('delete');

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Service in use by Project testing requirements');

        $this->deleteUseCase->execute($serviceId);
    }

    public function test_throws_exception_when_deleting_nonexistent_service()
    {
        $serviceId = 'nonexistent-id';
        
        $this->mockServiceRepository
            ->expects($this->once())
            ->method('findById')
            ->with($serviceId)
            ->willReturn(null);

        $this->mockServiceRepository
            ->expects($this->never())
            ->method('delete');

        $this->expectException(ServiceNotFoundException::class);
        $this->expectExceptionMessage("Service with ID {$serviceId} not found");

        $this->deleteUseCase->execute($serviceId);
    }

    public function test_cannot_update_service_name_to_duplicate_in_facility()
    {
        $serviceId = 'service-123';
        
        $mockService = $this->createMock(Service::class);
        $mockService->method('getFacilityId')->willReturn('facility-1');
        
        $mockExistingService = $this->createMock(Service::class);
        $mockExistingService->method('getId')->willReturn('different-service-id');

        $this->mockServiceRepository
            ->expects($this->once())
            ->method('findById')
            ->with($serviceId)
            ->willReturn($mockService);

        $this->mockServiceRepository
            ->expects($this->once())
            ->method('findByNameAndFacility')
            ->with('Existing Service', 'facility-1')
            ->willReturn($mockExistingService);

        $this->mockServiceRepository
            ->expects($this->never())
            ->method('save');

        $updateDto = new UpdateServiceDTO(
            id: $serviceId,
            name: 'Existing Service'
        );

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('A service with this name already exists in this facility');

        $this->updateUseCase->execute($updateDto);
    }
}
