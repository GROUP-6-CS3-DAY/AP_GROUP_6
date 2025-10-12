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
        $equipmentId = 'equip-123';
        
        // Create mock equipment
        $mockEquipment = $this->createMock(Equipment::class);
        $mockEquipment->method('getFacilityId')->willReturn('fac-1');
        // Don't set a return value for void method - just expect it to be called
        $mockEquipment->expects($this->once())->method('validateDeletionSafety');

        $this->mockEquipmentRepository
            ->expects($this->once())
            ->method('findById')
            ->with($equipmentId)
            ->willReturn($mockEquipment);

        $this->mockProjectRepository
            ->expects($this->once())
            ->method('findActiveProjectsByFacilityId')
            ->with('fac-1')
            ->willReturn([]); // No active projects

        $this->mockEquipmentRepository
            ->expects($this->once())
            ->method('delete')
            ->with($equipmentId);

        $this->useCase->execute($equipmentId);
    }

    public function test_cannot_delete_equipment_referenced_by_active_projects()
    {
        $equipmentId = 'equip-123';
        
        // Create mock equipment that throws exception during validation
        $mockEquipment = $this->createMock(Equipment::class);
        $mockEquipment->method('getFacilityId')->willReturn('fac-1');
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
            ->with('fac-1')
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

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Equipment not found');

        $this->useCase->execute($equipmentId);
    }
}
