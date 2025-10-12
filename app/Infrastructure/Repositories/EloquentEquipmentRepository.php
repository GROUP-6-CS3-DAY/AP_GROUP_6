<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Equipment;
use App\Domain\Repositories\EquipmentRepositoryInterface;
use App\Domain\ValueObjects\UsageDomain;
use App\Domain\ValueObjects\SupportPhase;
use App\Models\Equipment as EquipmentModel;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentEquipmentRepository implements EquipmentRepositoryInterface
{
    public function findById(string $id): ?Equipment
    {
        $model = EquipmentModel::with(['facility'])->find($id);
        
        if (!$model) {
            return null;
        }

        return $this->mapToEntity($model);
    }

    public function findWithFilters(array $filters, int $perPage = 15): array
    {
        $query = EquipmentModel::with(['facility']);

        if (!empty($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('description', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('inventory_code', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (!empty($filters['usage_domain'])) {
            $query->where('usage_domain', $filters['usage_domain']);
        }

        if (!empty($filters['support_phase'])) {
            $query->where('support_phase', $filters['support_phase']);
        }

        if (!empty($filters['facility_id'])) {
            $query->where('facility_id', $filters['facility_id']);
        }

        $paginatedResults = $query->paginate($perPage);
        
        // Transform the paginated results to domain entities
        $transformedItems = $paginatedResults->getCollection()->map(function($model) {
            return $this->mapToEntity($model);
        });

        // Create new paginator with transformed items
        $pagination = new LengthAwarePaginator(
            $transformedItems,
            $paginatedResults->total(),
            $paginatedResults->perPage(),
            $paginatedResults->currentPage(),
            [
                'path' => request()->url(),
                'pageName' => 'page',
            ]
        );

        return [
            'pagination' => $pagination,
            'data' => $transformedItems->toArray()
        ];
    }

    public function findByFacility(string $facilityId): array
    {
        return EquipmentModel::where('facility_id', $facilityId)
            ->get()
            ->map(fn($model) => $this->mapToEntity($model))
            ->toArray();
    }

    public function save(Equipment $equipment): void
    {
        $model = EquipmentModel::find($equipment->getId()) ?? new EquipmentModel();
        
        $model->fill([
            'id' => $equipment->getId(),
            'facility_id' => $equipment->getFacilityId(),
            'name' => $equipment->getName(),
            'capabilities' => $equipment->getCapabilities(),
            'description' => $equipment->getDescription(),
            'inventory_code' => $equipment->getInventoryCode(),
            'usage_domain' => $equipment->getUsageDomain()->getValue(),
            'support_phase' => $equipment->getSupportPhase()->getValue(),
        ]);

        $model->save();
    }

    public function delete(string $id): void
    {
        EquipmentModel::destroy($id);
    }

    public function findAllInventoryCodes(?string $excludeEquipmentId = null): array
    {
        $query = EquipmentModel::query();
        
        if ($excludeEquipmentId) {
            $query->where('id', '!=', $excludeEquipmentId);
        }
        
        return $query->pluck('inventory_code')->toArray();
    }

    private function mapToEntity(EquipmentModel $model): Equipment
    {
        return new Equipment(
            id: (string) $model->getKey(),
            facilityId: $model->facility_id,
            name: $model->name,
            capabilities: $model->capabilities ?? [],
            description: $model->description,
            inventoryCode: $model->inventory_code,
            usageDomain: new UsageDomain($model->usage_domain),
            supportPhase: new SupportPhase($model->support_phase)
        );
    }
}
