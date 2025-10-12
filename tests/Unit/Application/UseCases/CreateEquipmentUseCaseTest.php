<?php

namespace Tests\Unit\Application\UseCases;

use App\Application\UseCases\CreateEquipmentUseCase;
use App\Application\DTOs\CreateEquipmentDTO;
use App\Domain\Repositories\EquipmentRepositoryInterface;
use App\Domain\Entities\Equipment;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class CreateEquipmentUseCaseTest extends TestCase
{
    private EquipmentRepositoryInterface|MockObject $mockRepository;
    private CreateEquipmentUseCase $useCase;

    protected function setUp(): void
    {
        $this->mockRepository = $this->createMock(EquipmentRepositoryInterface::class);
        $this->useCase = new CreateEquipmentUseCase($this->mockRepository);
    }

    public function test_can_create_equipment_with_valid_data()
    {
        $dto = new CreateEquipmentDTO(
            facilityId: 'fac-1',
            name: 'CNC Machine XZ100',
            capabilities: ['cnc_machining', 'precision_cutting'],
            description: 'High precision CNC machining equipment',
            inventoryCode: 'CNC-001',
            usageDomain: 'mechanical',
            supportPhase: 'prototyping'
        );

        $this->mockRepository
            ->expects($this->once())
            ->method('findAllInventoryCodes')
            ->willReturn(['OTHER-001', 'TEST-002']); // No conflict

        $this->mockRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Equipment $equipment) {
                return $equipment->getName() === 'CNC Machine XZ100' &&
                       $equipment->getInventoryCode() === 'CNC-001' &&
                       $equipment->getUsageDomain()->getValue() === 'mechanical';
            }));

        $equipmentId = $this->useCase->execute($dto);

        $this->assertIsString($equipmentId);
        $this->assertNotEmpty($equipmentId);
    }

    public function test_throws_exception_when_inventory_code_already_exists()
    {
        $dto = new CreateEquipmentDTO(
            facilityId: 'fac-1',
            name: 'CNC Machine XZ100',
            capabilities: ['cnc_machining'],
            description: 'CNC equipment',
            inventoryCode: 'CNC-001',
            usageDomain: 'mechanical',
            supportPhase: 'prototyping'
        );

        $this->mockRepository
            ->expects($this->once())
            ->method('findAllInventoryCodes')
            ->willReturn(['CNC-001', 'OTHER-002']); // Conflict with CNC-001

        $this->mockRepository
            ->expects($this->never())
            ->method('save');

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Equipment.InventoryCode already exists');

        $this->useCase->execute($dto);
    }

    public function test_throws_exception_for_electronics_equipment_with_training_only()
    {
        $dto = new CreateEquipmentDTO(
            facilityId: 'fac-1',
            name: 'Electronics Board',
            capabilities: ['electronics_testing'],
            description: 'Electronics equipment',
            inventoryCode: 'ELEC-001',
            usageDomain: 'electronics', // Electronics domain
            supportPhase: 'training' // Training only - invalid for electronics
        );

        $this->mockRepository
            ->expects($this->once())
            ->method('findAllInventoryCodes')
            ->willReturn([]);

        $this->mockRepository
            ->expects($this->never())
            ->method('save');

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Electronics equipment must support Prototyping or Testing');

        $this->useCase->execute($dto);
    }
}
