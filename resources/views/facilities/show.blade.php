@extends('layouts.app')

@section('title', $facility->getName() . ' - Facility Details - InnoTrack')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-building me-2"></i>{{ $facility->getName() }}
            </h1>
            <div>
                <a href="{{ route('facilities.edit', $facility->getId()) }}" class="btn btn-warning me-2">
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
                        <p class="h5">{{ $facility->getName() }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Type</h6>
                        <span class="badge bg-secondary fs-6">{{ $facility->getFacilityType()->getDisplayName() }}</span>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Location</h6>
                        <p class="mb-0">
                            <i class="fas fa-map-marker-alt me-1"></i>{{ $facility->getLocation() }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Capacity</h6>
                        <p class="mb-0">
                            @if($facility->getCapacity() > 0)
                                <i class="fas fa-users me-1"></i>{{ $facility->getCapacity() }} people/projects
                            @else
                                <span class="text-muted">Not specified</span>
                            @endif
                        </p>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-12">
                        <h6 class="text-muted">Description</h6>
                        <p class="mb-0">{{ $facility->getDescription() }}</p>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Availability Status</h6>
                        @if($facility->isAvailable())
                            <span class="badge bg-success fs-6">Available</span>
                        @else
                            <span class="badge bg-warning fs-6">{{ ucfirst($facility->getAvailabilityStatus()) }}</span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Equipment Count</h6>
                        <p class="mb-0">
                            @if($facility->hasEquipment())
                                <span class="badge bg-info">{{ count($facility->getEquipmentList()) }}</span> items
                            @else
                                <span class="text-muted">No equipment listed</span>
                            @endif
                        </p>
                    </div>
                </div>

                @if($facility->hasCapabilities())
                <hr>
                <div class="mb-3">
                    <h6 class="text-muted">Capabilities</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($facility->getCapabilities() as $capability)
                        <span class="badge bg-primary">{{ $capability }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($facility->hasEquipment())
                <hr>
                <div class="mb-3">
                    <h6 class="text-muted">Equipment List</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($facility->getEquipmentList() as $equipment)
                        <span class="badge bg-success">{{ $equipment }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Services Section (Placeholder) -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-cogs me-2"></i>Services
                    <span class="badge bg-info ms-2">0</span>
                </h5>
                <a href="#" class="btn btn-sm btn-success disabled">
                    <i class="fas fa-plus me-1"></i>Add Service
                </a>
            </div>
            <div class="card-body">
                <div class="text-center py-3">
                    <i class="fas fa-cogs fa-2x text-muted mb-2"></i>
                    <p class="text-muted mb-0">Services functionality will be available once service management is implemented</p>
                </div>
            </div>
        </div>

        <!-- Projects Section (Placeholder) -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-project-diagram me-2"></i>Projects
                    <span class="badge bg-warning ms-2">0</span>
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center py-3">
                    <i class="fas fa-project-diagram fa-2x text-muted mb-2"></i>
                    <p class="text-muted mb-0">Associated projects will be shown here once project relationships are implemented</p>
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
                    <a href="{{ route('facilities.edit', $facility->getId()) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i>Edit Facility
                    </a>
                    <button class="btn btn-success disabled">
                        <i class="fas fa-plus me-1"></i>Add Service
                    </button>
                    <button class="btn btn-info disabled">
                        <i class="fas fa-plus me-1"></i>Add Equipment
                    </button>
                    <button class="btn btn-outline-danger" onclick="confirmDelete()">
                        <i class="fas fa-trash me-1"></i>Delete Facility
                    </button>
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
                        <h4 class="text-primary">0</h4>
                        <small class="text-muted">Services</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success">{{ $facility->hasEquipment() ? count($facility->getEquipmentList()) : 0 }}</h4>
                        <small class="text-muted">Equipment Items</small>
                    </div>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col-6">
                        <h4 class="text-info">0</h4>
                        <small class="text-muted">Active Projects</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-warning">{{ $facility->hasCapabilities() ? count($facility->getCapabilities()) : 0 }}</h4>
                        <small class="text-muted">Capabilities</small>
                    </div>
                </div>
                @if($facility->getCapacity() > 0)
                <hr>
                <div class="text-center">
                    <h4 class="text-secondary">{{ $facility->getCapacity() }}</h4>
                    <small class="text-muted">Max Capacity</small>
                </div>
                @endif
            </div>
        </div>

        <!-- Facility Status Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Facility Status
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <span class="fw-bold">Status:</span>
                    @if($facility->isAvailable())
                        <span class="badge bg-success ms-2">Available</span>
                    @else
                        <span class="badge bg-warning ms-2">{{ ucfirst($facility->getAvailabilityStatus()) }}</span>
                    @endif
                </div>

                @if($facility->getCapacity() > 0)
                <div class="mb-3">
                    <span class="fw-bold">Can Accommodate:</span>
                    <div class="progress mt-2" style="height: 20px;">
                        <div class="progress-bar bg-info" role="progressbar" style="width: 100%;">
                            Up to {{ $facility->getCapacity() }} people/projects
                        </div>
                    </div>
                </div>
                @endif

                <div class="mb-3">
                    <span class="fw-bold">Type:</span>
                    <span class="ms-2">{{ $facility->getFacilityType()->getDisplayName() }}</span>
                </div>

                <div>
                    <span class="fw-bold">ID:</span>
                    <code class="ms-2">{{ $facility->getId() }}</code>
                </div>
            </div>
        </div>

        <!-- Capabilities Breakdown -->
        @if($facility->hasCapabilities())
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-list me-2"></i>Available Capabilities
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-1">
                    @foreach($facility->getCapabilities() as $capability)
                    <span class="badge bg-primary">{{ str_replace('_', ' ', ucwords($capability, '_')) }}</span>
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
                <p>Are you sure you want to delete the facility "<strong>{{ $facility->getName() }}</strong>"?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> This action cannot be undone. All associated data will be permanently removed.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('facilities.destroy', $facility->getId()) }}" method="POST" class="d-inline">
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

@push('styles')
<style>
    .badge {
        font-size: 0.875em;
    }
    
    .progress-bar {
        font-size: 0.875em;
        font-weight: 500;
    }
    
    .card-header .badge {
        font-size: 0.75em;
    }
</style>
@endpush