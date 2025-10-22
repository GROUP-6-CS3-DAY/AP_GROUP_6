<?php

namespace Tests\Unit\Application\UseCases;

use App\Application\UseCases\DeleteEquipmentUseCase;
use App\Domain\Repositories\EquipmentRepositoryInterface;
use App\Domain\Repositories\ProjectRepositoryInterface;
use App\Domain\Entities\Equipment;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class DeleteEquipmentUseCaseTest extends TestCase
{
    private EquipmentRepositoryInterface|MockObject $mockEquipmentRepository;
    private ProjectRepositoryInterface|MockObject $mockProjectRepository;
    private DeleteEquipmentUseCase $useCase;

    protected function setUp(): void
    {
        $this->mockEquipmentRepository = $this->createMock(EquipmentRepositoryInterface::class);
        $this->mockProjectRepository = $this->createMock(ProjectRepositoryInterface::class);
        $this->useCase = new DeleteEquipmentUseCase($this->mockEquipmentRepository, $this->mockProjectRepository);
    }

    public function test_can_delete_equipment_when_not_referenced_by_active_projects()
    {
        $equipmentId = 'equipment-123';
        $facilityId = 'facility-456';
        
        // Create mock with proper method configuration
        $mockEquipment = $this->getMockBuilder(Equipment::class)
            ->disableOriginalConstructor()
            ->addMethods(['getStatus'])
            ->onlyMethods(['getFacilityId', 'validateDeletionSafety'])
            ->getMock();
        
        $mockEquipment->method('getFacilityId')->willReturn($facilityId);
        $mockEquipment->expects($this->once())
            ->method('validateDeletionSafety');
        
        // Mock equipment status
        $mockStatus = $this->getMockBuilder(\stdClass::class)
            ->addMethods(['getValue'])
            ->getMock();
        $mockStatus->method('getValue')->willReturn('decommissioned');
        $mockEquipment->method('getStatus')->willReturn($mockStatus);

        $this->mockEquipmentRepository
            ->expects($this->once())
            ->method('findById')
            ->with($equipmentId)
            ->willReturn($mockEquipment);

        $this->mockProjectRepository
            ->expects($this->once())
            ->method('findActiveProjectsByFacilityId')
            ->with($facilityId)
            ->willReturn([]);

        $this->mockEquipmentRepository
            ->expects($this->once())
            ->method('delete')
            ->with($equipmentId);

        $this->useCase->execute($equipmentId);
    }

    public function test_cannot_delete_operational_equipment()
    {
        $equipmentId = 'equipment-123';
        $facilityId = 'facility-456';
        
        $mockEquipment = $this->getMockBuilder(Equipment::class)
            ->disableOriginalConstructor()
            ->addMethods(['getStatus'])
            ->onlyMethods(['getFacilityId', 'validateDeletionSafety'])
            ->getMock();
        
        $mockEquipment->method('getFacilityId')->willReturn($facilityId);
        $mockEquipment->expects($this->once())
            ->method('validateDeletionSafety');
        
        $mockStatus = $this->getMockBuilder(\stdClass::class)
            ->addMethods(['getValue'])
            ->getMock();
        $mockStatus->method('getValue')->willReturn('operational');
        $mockEquipment->method('getStatus')->willReturn($mockStatus);

        $this->mockEquipmentRepository
            ->expects($this->once())
            ->method('findById')
            ->with($equipmentId)
            ->willReturn($mockEquipment);

        $this->mockProjectRepository
            ->expects($this->once())
            ->method('findActiveProjectsByFacilityId')
            ->with($facilityId)
            ->willReturn([]);

        $this->mockEquipmentRepository
            ->expects($this->never())
            ->method('delete');

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Cannot delete operational equipment');

        $this->useCase->execute($equipmentId);
    }

    public function test_cannot_delete_equipment_in_use()
    {
        $equipmentId = 'equipment-123';
        $facilityId = 'facility-456';
        
        $mockEquipment = $this->getMockBuilder(Equipment::class)
            ->disableOriginalConstructor()
            ->addMethods(['getStatus'])
            ->onlyMethods(['getFacilityId', 'validateDeletionSafety'])
            ->getMock();
        
        $mockEquipment->method('getFacilityId')->willReturn($facilityId);
        $mockEquipment->expects($this->once())
            ->method('validateDeletionSafety');
        
        $mockStatus = $this->getMockBuilder(\stdClass::class)
            ->addMethods(['getValue'])
            ->getMock();
        $mockStatus->method('getValue')->willReturn('in_use');
        $mockEquipment->method('getStatus')->willReturn($mockStatus);

        $this->mockEquipmentRepository
            ->expects($this->once())
            ->method('findById')
            ->with($equipmentId)
            ->willReturn($mockEquipment);

        $this->mockProjectRepository
            ->expects($this->once())
            ->method('findActiveProjectsByFacilityId')
            ->with($facilityId)
            ->willReturn([]);

        $this->mockEquipmentRepository
            ->expects($this->never())
            ->method('delete');

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Cannot delete equipment that is currently in use');

        $this->useCase->execute($equipmentId);
    }

    public function test_cannot_delete_equipment_referenced_by_active_projects()
    {
        $equipmentId = 'equip-123';
        $facilityId = 'fac-1';
        
        // Create mock equipment that throws exception during validation
        $mockEquipment = $this->createMock(Equipment::class);
        $mockEquipment->method('getFacilityId')->willReturn($facilityId);
        $mockEquipment->expects($this->once())
            ->method('validateDeletionSafety')
            ->willThrowException(new \DomainException('Equipment referenced by active Project'));

        $this->mockEquipmentRepository
            ->expects($this->once())
            ->method('findById')
            ->with($equipmentId)
            ->willReturn($mockEquipment);

        $this->mockProjectRepository
            ->expects($this->once())
            ->method('findActiveProjectsByFacilityId')
            ->with($facilityId)
            ->willReturn([
                [
                    'id' => 'proj-1',
                    'status' => 'active',
                    'equipment_ids' => ['equip-123']
                ]
            ]);

        $this->mockEquipmentRepository
            ->expects($this->never())
            ->method('delete');

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Equipment referenced by active Project');

        $this->useCase->execute($equipmentId);
    }

    public function test_throws_exception_when_equipment_not_found()
    {
        $equipmentId = 'non-existent-123';

        $this->mockEquipmentRepository
            ->expects($this->once())
            ->method('findById')
            ->with($equipmentId)
            ->willReturn(null);

        $this->mockProjectRepository
            ->expects($this->never())
            ->method('findActiveProjectsByFacilityId');

        $this->mockEquipmentRepository
            ->expects($this->never())
            ->method('delete');

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Equipment not found');

        $this->useCase->execute($equipmentId);
    }
}
