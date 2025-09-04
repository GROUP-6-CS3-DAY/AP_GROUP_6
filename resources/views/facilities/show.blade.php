@extends('layouts.app')

@section('title', $facility->name . ' - Facility Details - InnoTrack')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-building me-2"></i>{{ $facility->name }}
            </h1>
            <div>
                <a href="{{ route('facilities.edit', $facility) }}" class="btn btn-warning me-2">
                    <i class="fas fa-edit me-1"></i>Edit Facility
                </a>
                <a href="{{ route('facilities.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back to Facilities
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Facility Details -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Facility Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Facility Name</h6>
                        <p class="h5">{{ $facility->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Type</h6>
                        <span class="badge bg-secondary fs-6">{{ $facility->getFacilityTypeOptions()[$facility->facility_type] ?? $facility->facility_type }}</span>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Location</h6>
                        <p class="mb-0">
                            <i class="fas fa-map-marker-alt me-1"></i>{{ $facility->location }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Partner Organization</h6>
                        <p class="mb-0">{{ $facility->partner_organization }}</p>
                    </div>
                </div>

                <hr>

                <div class="mb-3">
                    <h6 class="text-muted">Description</h6>
                    <p class="mb-0">{{ $facility->description }}</p>
                </div>

                <hr>

                <div class="mb-3">
                    <h6 class="text-muted">Capabilities</h6>
                    @if($facility->capabilities && count($facility->capabilities) > 0)
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($facility->capabilities as $capability)
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
                        <p>{{ $facility->created_at->format('M d, Y \a\t g:i A') }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Last Updated</h6>
                        <p>{{ $facility->updated_at->format('M d, Y \a\t g:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Services Section -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-cogs me-2"></i>Services
                    <span class="badge bg-info ms-2">{{ $facility->services->count() }}</span>
                </h5>
                <a href="{{ route('services.create', ['facility_id' => $facility->facility_id]) }}" class="btn btn-sm btn-success">
                    <i class="fas fa-plus me-1"></i>Add Service
                </a>
            </div>
            <div class="card-body">
                @if($facility->services->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Service Name</th>
                                <th>Category</th>
                                <th>Skill Type</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($facility->services->take(5) as $service)
                            <tr>
                                <td>
                                    <a href="{{ route('services.show', $service) }}" class="text-decoration-none">
                                        {{ $service->name }}
                                    </a>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $service->getCategoryOptions()[$service->category] ?? $service->category }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-warning">{{ $service->getSkillTypeOptions()[$service->skill_type] ?? $service->skill_type }}</span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('services.show', $service) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('services.edit', $service) }}" class="btn btn-outline-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($facility->services->count() > 5)
                <div class="text-center mt-3">
                    <a href="{{ route('services.by-facility', $facility) }}" class="btn btn-outline-primary">
                        View All Services
                    </a>
                </div>
                @endif
                @else
                <div class="text-center py-3">
                    <i class="fas fa-cogs fa-2x text-muted mb-2"></i>
                    <p class="text-muted mb-0">No services available at this facility</p>
                    <a href="{{ route('services.create', ['facility_id' => $facility->facility_id]) }}" class="btn btn-success btn-sm mt-2">
                        <i class="fas fa-plus me-1"></i>Add First Service
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- Equipment Section -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-tools me-2"></i>Equipment
                    <span class="badge bg-success ms-2">{{ $facility->equipment->count() }}</span>
                </h5>
                <a href="{{ route('equipment.create', ['facility_id' => $facility->facility_id]) }}" class="btn btn-sm btn-success">
                    <i class="fas fa-plus me-1"></i>Add Equipment
                </a>
            </div>
            <div class="card-body">
                @if($facility->equipment->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Equipment Name</th>
                                <th>Usage Domain</th>
                                <th>Support Phase</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($facility->equipment->take(5) as $equipment)
                            <tr>
                                <td>
                                    <a href="{{ route('equipment.show', $equipment) }}" class="text-decoration-none">
                                        {{ $equipment->name }}
                                    </a>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $equipment->getUsageDomainOptions()[$equipment->usage_domain] ?? $equipment->usage_domain }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-warning">{{ $equipment->getSupportPhaseOptions()[$equipment->support_phase] ?? $equipment->support_phase }}</span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('equipment.show', $equipment) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('equipment.edit', $equipment) }}" class="btn btn-outline-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($facility->equipment->count() > 5)
                <div class="text-center mt-3">
                    <a href="{{ route('equipment.by-facility', $facility) }}" class="btn btn-outline-primary">
                        View All Equipment
                    </a>
                </div>
                @endif
                @else
                <div class="text-center py-3">
                    <i class="fas fa-tools fa-2x text-muted mb-2"></i>
                    <p class="text-muted mb-0">No equipment available at this facility</p>
                    <a href="{{ route('equipment.create', ['facility_id' => $facility->facility_id]) }}" class="btn btn-success btn-sm mt-2">
                        <i class="fas fa-plus me-1"></i>Add First Equipment
                    </a>
                </div>
                @endif
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
                    <a href="{{ route('facilities.edit', $facility) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i>Edit Facility
                    </a>
                    <a href="{{ route('services.create', ['facility_id' => $facility->facility_id]) }}" class="btn btn-success">
                        <i class="fas fa-plus me-1"></i>Add Service
                    </a>
                    <a href="{{ route('equipment.create', ['facility_id' => $facility->facility_id]) }}" class="btn btn-info">
                        <i class="fas fa-plus me-1"></i>Add Equipment
                    </a>
                </div>
            </div>
        </div>

        <!-- Facility Statistics -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Statistics
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h4 class="text-primary">{{ $facility->services->count() }}</h4>
                        <small class="text-muted">Total Services</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success">{{ $facility->equipment->count() }}</h4>
                        <small class="text-muted">Total Equipment</small>
                    </div>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col-6">
                        <h4 class="text-info">{{ $facility->projects->count() }}</h4>
                        <small class="text-muted">Active Projects</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-warning">{{ count($facility->capabilities ?? []) }}</h4>
                        <small class="text-muted">Capabilities</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Capabilities Breakdown -->
        @if($facility->capabilities && count($facility->capabilities) > 0)
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-list me-2"></i>Capabilities
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-1">
                    @foreach($facility->capabilities as $capability)
                    <span class="badge bg-primary">{{ $capability }}</span>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Facility</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the facility "<strong>{{ $facility->name }}</strong>"?</p>
                <p class="text-danger"><small>This will also delete all associated services and equipment.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('facilities.destroy', $facility) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Facility</button>
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