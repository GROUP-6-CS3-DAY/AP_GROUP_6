<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\FacilityRepositoryInterface;
use App\Domain\Entities\Facility;
use App\Domain\ValueObjects\FacilityType;
use App\Models\Facility as FacilityModel;

class EloquentFacilityRepository implements FacilityRepositoryInterface
{
    public function findById(string $id): ?Facility
    {
        $model = FacilityModel::find($id);
        
        return $model ? $this->toDomainEntity($model) : null;
    }

    public function findAll(): array
    {
        return FacilityModel::all()
            ->map(fn($model) => $this->toDomainEntity($model))
            ->toArray();
    }

    public function findByNameAndLocation(string $name, string $location): ?Facility
    {
        $model = FacilityModel::where('name', $name)
            ->where('location', $location)
            ->first();
            
        return $model ? $this->toDomainEntity($model) : null;
    }

    public function hasDependentRecords(string $id): bool
    {
        $facility = FacilityModel::find($id);
        
        if (!$facility) {
            return false;
        }

        // Check for dependent records (Services, Equipment, Projects)
        return $facility->services()->exists() || 
               $facility->equipment()->exists() || 
               $facility->projects()->exists();
    }

    public function save(Facility $facility): void
    {
        // Check uniqueness constraint
        $existing = $this->findByNameAndLocation($facility->getName(), $facility->getLocation());
        if ($existing && $existing->getId() !== $facility->getId()) {
            throw new \DomainException('A facility with this name already exists at this location');
        }

        // For updates, find existing model by ID, for new records create new model
        $model = null;
        if ($facility->getId() && is_numeric($facility->getId())) {
            $model = FacilityModel::find($facility->getId());
        }
        
        if (!$model) {
            $model = new FacilityModel();
        }

        $model->fill([
            'name' => $facility->getName(),
            'description' => $facility->getDescription(),
            'location' => $facility->getLocation(),
            'facility_type' => $facility->getFacilityType()->getValue(),
            'capacity' => $facility->getCapacity(),
            'equipment_list' => $facility->getEquipmentList(),
            'capabilities' => $facility->getCapabilities(),
            'availability_status' => $facility->getAvailabilityStatus(),
        ]);

        $model->save();
    }

    public function delete(string $id): void
    {
        if ($this->hasDependentRecords($id)) {
            throw new \DomainException('Facility has dependent records (Services/Equipment/Projects)');
        }
        
        FacilityModel::destroy($id);
    }

    private function toDomainEntity(FacilityModel $model): Facility
    {
        return new Facility(
            id: $model->id,
            name: $model->name,
            description: $model->description ?? '',
            location: $model->location,
            facilityType: new FacilityType($model->facility_type),
            capacity: $model->capacity ?? 0,
            equipmentList: $model->equipment_list ?? [],
            capabilities: $model->capabilities ?? [],
            availabilityStatus: $model->availability_status ?? 'available'
        );


    }}