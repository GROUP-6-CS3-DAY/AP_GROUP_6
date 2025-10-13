<?php

namespace Tests\Unit\Application\UseCases;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Application\UseCases\CreateFacilityUseCase;
use App\Application\UseCases\UpdateFacilityUseCase;
use App\Application\UseCases\DeleteFacilityUseCase;
use App\Application\DTOs\CreateFacilityDTO;
use App\Application\DTOs\UpdateFacilityDTO;
use App\Domain\Repositories\FacilityRepositoryInterface;
use App\Domain\Entities\Facility;
use App\Domain\ValueObjects\FacilityType;
use App\Application\Exceptions\FacilityNotFoundException;

class FacilityUseCasesTest extends TestCase
{
    private FacilityRepositoryInterface|MockObject $mockRepository;
    private CreateFacilityUseCase $createUseCase;
    private UpdateFacilityUseCase $updateUseCase;
    private DeleteFacilityUseCase $deleteUseCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockRepository = $this->createMock(FacilityRepositoryInterface::class);
        $this->createUseCase = new CreateFacilityUseCase($this->mockRepository);
        $this->updateUseCase = new UpdateFacilityUseCase($this->mockRepository);
        $this->deleteUseCase = new DeleteFacilityUseCase($this->mockRepository);
    }

    public function test_can_create_facility()
    {
        $dto = new CreateFacilityDTO(
            name: 'Test Facility',
            description: 'Test Description',
            location: 'Test Location',
            facilityType: 'workshop',
            capacity: 50,
            equipmentList: ['CNC Machine', '3D Printer'],
            capabilities: ['cnc_machining', '3d_printing'],
            availabilityStatus: 'available'
        );

        $this->mockRepository
            ->expects($this->once())
            ->method('findByNameAndLocation')
            ->with('Test Facility', 'Test Location')
            ->willReturn(null);

        $this->mockRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Facility $facility) {
                return $facility->getName() === 'Test Facility' &&
                       $facility->getLocation() === 'Test Location' &&
                       $facility->getFacilityType()->getValue() === 'workshop';
            }));

        $facilityId = $this->createUseCase->execute($dto);

        $this->assertIsString($facilityId);
        $this->assertNotEmpty($facilityId);
    }

    public function test_cannot_create_facility_with_duplicate_name_location()
    {
        $dto = new CreateFacilityDTO(
            name: 'Test Facility',
            description: 'Test Description',
            location: 'Test Location',
            facilityType: 'workshop',
            capacity: 50,
            equipmentList: [],
            capabilities: [],
            availabilityStatus: 'available'
        );

        $existingFacility = $this->createMock(Facility::class);
        $this->mockRepository
            ->expects($this->once())
            ->method('findByNameAndLocation')
            ->with('Test Facility', 'Test Location')
            ->willReturn($existingFacility);

        $this->mockRepository
            ->expects($this->never())
            ->method('save');

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('A facility with this name already exists at this location');

        $this->createUseCase->execute($dto);
    }

    public function test_can_update_facility()
    {
        $facilityId = 'facility-123';
        
        $mockFacility = $this->createMock(Facility::class);
        $mockFacility->expects($this->once())
            ->method('update')
            ->with([
                'name' => 'Updated Facility',
                'description' => 'Updated Description',
                'capabilities' => ['cnc_machining', '3d_printing']
            ]);

        $this->mockRepository
            ->expects($this->once())
            ->method('findById')
            ->with($facilityId)
            ->willReturn($mockFacility);

        $this->mockRepository
            ->expects($this->once())
            ->method('save')
            ->with($mockFacility);

        $updateDto = new UpdateFacilityDTO(
            id: $facilityId,
            name: 'Updated Facility',
            description: 'Updated Description',
            capabilities: ['cnc_machining', '3d_printing']
        );

        $this->updateUseCase->execute($updateDto);
    }

    public function test_throws_exception_when_updating_nonexistent_facility()
    {
        $facilityId = 'nonexistent-id';
        
        $this->mockRepository
            ->expects($this->once())
            ->method('findById')
            ->with($facilityId)
            ->willReturn(null);

        $this->mockRepository
            ->expects($this->never())
            ->method('save');

        $updateDto = new UpdateFacilityDTO(
            id: $facilityId,
            name: 'Updated Facility'
        );

        $this->expectException(FacilityNotFoundException::class);
        $this->expectExceptionMessage("Facility with ID {$facilityId} not found");

        $this->updateUseCase->execute($updateDto);
    }

    public function test_can_delete_facility_without_dependencies()
    {
        $facilityId = 'facility-123';
        
        $mockFacility = $this->createMock(Facility::class);

        $this->mockRepository
            ->expects($this->once())
            ->method('findById')
            ->with($facilityId)
            ->willReturn($mockFacility);

        $this->mockRepository
            ->expects($this->once())
            ->method('delete')
            ->with($facilityId);

        $this->deleteUseCase->execute($facilityId);
    }

    public function test_cannot_delete_facility_with_dependencies()
    {
        $facilityId = 'facility-123';
        
        $mockFacility = $this->createMock(Facility::class);

        $this->mockRepository
            ->expects($this->once())
            ->method('findById')
            ->with($facilityId)
            ->willReturn($mockFacility);

        $this->mockRepository
            ->expects($this->once())
            ->method('delete')
            ->with($facilityId)
            ->willThrowException(new \DomainException('Facility has dependent records (Services/Equipment/Projects)'));

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Facility has dependent records (Services/Equipment/Projects)');

        $this->deleteUseCase->execute($facilityId);
    }

    public function test_throws_exception_when_deleting_nonexistent_facility()
    {
        $facilityId = 'nonexistent-id';
        
        $this->mockRepository
            ->expects($this->once())
            ->method('findById')
            ->with($facilityId)
            ->willReturn(null);

        $this->mockRepository
            ->expects($this->never())
            ->method('delete');

        $this->expectException(FacilityNotFoundException::class);
        $this->expectExceptionMessage("Facility with ID {$facilityId} not found");

        $this->deleteUseCase->execute($facilityId);
    }
}
