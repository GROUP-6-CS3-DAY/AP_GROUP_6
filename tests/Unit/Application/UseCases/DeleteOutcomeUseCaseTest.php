<?php

namespace Tests\Unit\Application\UseCases;

use App\Application\UseCases\DeleteOutcomeUseCase;
use App\Domain\Repositories\OutcomeRepositoryInterface;
use App\Domain\Entities\Outcome;
use App\Domain\ValueObjects\CommercializationStatus;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class DeleteOutcomeUseCaseTest extends TestCase
{
    private OutcomeRepositoryInterface|MockObject $mockRepository;
    private DeleteOutcomeUseCase $useCase;

    protected function setUp(): void
    {
        $this->mockRepository = $this->createMock(OutcomeRepositoryInterface::class);
        $this->useCase = new DeleteOutcomeUseCase($this->mockRepository);
    }

    public function test_can_delete_outcome_when_exists()
    {
        $outcomeId = 'outcome-123';
        
        // Create mock outcome with non-commercialized status
        $mockOutcome = $this->createMock(Outcome::class);
        $mockCommercializationStatus = $this->createMock(CommercializationStatus::class);
        $mockCommercializationStatus->method('getValue')->willReturn('ready');
        $mockOutcome->method('getCommercializationStatus')->willReturn($mockCommercializationStatus);

        $this->mockRepository
            ->expects($this->once())
            ->method('findById')
            ->with($outcomeId)
            ->willReturn($mockOutcome);

        $this->mockRepository
            ->expects($this->once())
            ->method('delete')
            ->with($outcomeId);

        $this->useCase->execute($outcomeId);
    }

    public function test_cannot_delete_outcome_with_business_rule_violations()
    {
        $outcomeId = 'outcome-123';
        
        // Create mock outcome with commercialized status
        $mockOutcome = $this->createMock(Outcome::class);
        $mockCommercializationStatus = $this->createMock(CommercializationStatus::class);
        $mockCommercializationStatus->method('getValue')->willReturn('commercialized');
        $mockOutcome->method('getCommercializationStatus')->willReturn($mockCommercializationStatus);

        $this->mockRepository
            ->expects($this->once())
            ->method('findById')
            ->with($outcomeId)
            ->willReturn($mockOutcome);

        $this->mockRepository
            ->expects($this->never())
            ->method('delete');

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Cannot delete outcome that is referenced by active commercialization');

        $this->useCase->execute($outcomeId);
    }

    public function test_throws_exception_when_outcome_not_found()
    {
        $outcomeId = 'non-existent-123';

        $this->mockRepository
            ->expects($this->once())
            ->method('findById')
            ->with($outcomeId)
            ->willReturn(null);

        $this->mockRepository
            ->expects($this->never())
            ->method('delete');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Outcome not found');

        $this->useCase->execute($outcomeId);
    }
}
