@extends('layouts.app')

@section('title', $equipment->name . ' - Equipment Details - InnoTrack')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-tools me-2"></i>{{ $equipment->name }}
            </h1>
            <div>
                <a href="{{ route('equipment.edit', $equipment) }}" class="btn btn-warning me-2">
                    <i class="fas fa-edit me-1"></i>Edit Equipment
                </a>
                <a href="{{ route('equipment.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back to Equipment
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Equipment Details -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Equipment Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Equipment Name</h6>
                        <p class="h5">{{ $equipment->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Inventory Code</h6>
                        <code class="h6">{{ $equipment->inventory_code }}</code>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Usage Domain</h6>
                        <span class="badge bg-info fs-6">{{ $equipment->getUsageDomainOptions()[$equipment->usage_domain] ?? $equipment->usage_domain }}</span>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Support Phase</h6>
                        <span class="badge bg-warning fs-6">{{ $equipment->getSupportPhaseOptions()[$equipment->support_phase] ?? $equipment->support_phase }}</span>
                    </div>
                </div>

                <hr>

                <div class="mb-3">
                    <h6 class="text-muted">Description</h6>
                    <p class="mb-0">{{ $equipment->description }}</p>
                </div>

                <hr>

                <div class="mb-3">
                    <h6 class="text-muted">Capabilities</h6>
                    @if($equipment->capabilities && count($equipment->capabilities) > 0)
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($equipment->capabilities as $capability)
                        <span class="badge bg-primary">{{ $capability }}</span>
                        @endforeach
                    </div>
                    @else
                    <p class="text-muted mb-0">No capabilities listed</p>
                    @endif
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Created</h6>
                        <p>{{ $equipment->created_at->format('M d, Y \a\t g:i A') }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Last Updated</h6>
                        <p>{{ $equipment->updated_at->format('M d, Y \a\t g:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Facility Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-building me-2"></i>Host Facility
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Facility Name</h6>
                        <p class="h6">
                            <a href="{{ route('facilities.show', $equipment->facility) }}" class="text-decoration-none">
                                <i class="fas fa-building me-1"></i>{{ $equipment->facility->name }}
                            </a>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Facility Type</h6>
                        <span class="badge bg-secondary">{{ $equipment->facility->getFacilityTypeOptions()[$equipment->facility->facility_type] ?? $equipment->facility->facility_type }}</span>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Location</h6>
                        <p class="mb-0">{{ $equipment->facility->location }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Partner Organization</h6>
                        <p class="mb-0">{{ $equipment->facility->partner_organization }}</p>
                    </div>
                </div>

                <hr>

                <div class="mb-3">
                    <h6 class="text-muted">Facility Description</h6>
                    <p class="mb-0">{{ $equipment->facility->description }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('equipment.edit', $equipment) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i>Edit Equipment
                    </a>
                    <a href="{{ route('facilities.show', $equipment->facility) }}" class="btn btn-outline-primary">
                        <i class="fas fa-building me-1"></i>View Facility
                    </a>
                    <a href="{{ route('equipment.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-list me-1"></i>All Equipment
                    </a>
                </div>
            </div>
        </div>

        <!-- Equipment Statistics -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Statistics
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h4 class="text-primary">{{ $equipment->facility->equipment->count() }}</h4>
                        <small class="text-muted">Total Equipment</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success">{{ $equipment->facility->services->count() }}</h4>
                        <small class="text-muted">Total Services</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Equipment -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-tools me-2"></i>Other Equipment at {{ $equipment->facility->name }}
                </h6>
            </div>
            <div class="card-body">
                @php
                $otherEquipment = $equipment->facility->equipment->where('equipment_id', '!=', $equipment->equipment_id)->take(5);
                @endphp

                @if($otherEquipment->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach($otherEquipment as $otherItem)
                    <a href="{{ route('equipment.show', $otherItem) }}" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">{{ $otherItem->name }}</h6>
                            <small class="text-muted">{{ $otherItem->getUsageDomainOptions()[$otherItem->usage_domain] ?? $otherItem->usage_domain }}</small>
                        </div>
                        <small class="text-muted">{{ Str::limit($otherItem->description, 50) }}</small>
                    </a>
                    @endforeach
                </div>
                @if($equipment->facility->equipment->count() > 6)
                <div class="text-center mt-2">
                    <a href="{{ route('equipment.index', ['facility_id' => $equipment->facility->facility_id]) }}" class="btn btn-sm btn-outline-primary">
                        View All Equipment
                    </a>
                </div>
                @endif
                @else
                <p class="text-muted text-center mb-0">No other equipment at this facility</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Equipment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the equipment "<strong>{{ $equipment->name }}</strong>"?</p>
                <p class="text-danger"><small>This action cannot be undone.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('equipment.destroy', $equipment) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Equipment</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Delete confirmation
    function confirmDelete() {
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }
</script>
@endpush