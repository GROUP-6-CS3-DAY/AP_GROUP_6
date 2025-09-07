@extends('layouts.app')

@section('title', $service->name . ' - Service Details - InnoTrack')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-cogs me-2"></i>{{ $service->name }}
            </h1>
            <div>
                <a href="{{ route('services.edit', $service) }}" class="btn btn-warning me-2">
                    <i class="fas fa-edit me-1"></i>Edit Service
                </a>
                <a href="{{ route('services.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back to Services
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Service Details -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Service Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Service Name</h6>
                        <p class="h5">{{ $service->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Category</h6>
                        <span class="badge bg-info fs-6">{{ $service->getCategoryOptions()[$service->category] ?? $service->category }}</span>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Skill Type</h6>
                        <span class="badge bg-warning fs-6">{{ $service->getSkillTypeOptions()[$service->skill_type] ?? $service->skill_type }}</span>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Created</h6>
                        <p>{{ $service->created_at->format('M d, Y \a\t g:i A') }}</p>
                    </div>
                </div>

                <hr>

                <div class="mb-3">
                    <h6 class="text-muted">Description</h6>
                    <p class="mb-0">{{ $service->description }}</p>
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
                            <a href="{{ route('facilities.show', $service->facility) }}" class="text-decoration-none">
                                <i class="fas fa-building me-1"></i>{{ $service->facility->name }}
                            </a>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Facility Type</h6>
                        <span class="badge bg-secondary">{{ $service->facility->getFacilityTypeOptions()[$service->facility->facility_type] ?? $service->facility->facility_type }}</span>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Location</h6>
                        <p class="mb-0">{{ $service->facility->location }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Partner Organization</h6>
                        <p class="mb-0">{{ $service->facility->partner_organization }}</p>
                    </div>
                </div>

                <hr>

                <div class="mb-3">
                    <h6 class="text-muted">Facility Description</h6>
                    <p class="mb-0">{{ $service->facility->description }}</p>
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
                    <a href="{{ route('services.edit', $service) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i>Edit Service
                    </a>
                    <a href="{{ route('facilities.show', $service->facility) }}" class="btn btn-outline-primary">
                        <i class="fas fa-building me-1"></i>View Facility
                    </a>
                    <a href="{{ route('services.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-list me-1"></i>All Services
                    </a>
                </div>
            </div>
        </div>

        <!-- Service Statistics -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Statistics
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h4 class="text-primary">{{ $service->facility->services->count() }}</h4>
                        <small class="text-muted">Total Services</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success">{{ $service->facility->equipment->count() }}</h4>
                        <small class="text-muted">Total Equipment</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Services -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-cogs me-2"></i>Other Services at {{ $service->facility->name }}
                </h6>
            </div>
            <div class="card-body">
                @php
                $otherServices = $service->facility->services->where('service_id', '!=', $service->service_id)->take(5);
                @endphp

                @if($otherServices->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach($otherServices as $otherService)
                    <a href="{{ route('services.show', $otherService) }}" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">{{ $otherService->name }}</h6>
                            <small class="text-muted">{{ $otherService->getCategoryOptions()[$otherService->category] ?? $otherService->category }}</small>
                        </div>
                        <small class="text-muted">{{ Str::limit($otherService->description, 50) }}</small>
                    </a>
                    @endforeach
                </div>
                @if($service->facility->services->count() > 6)
                <div class="text-center mt-2">
                    <a href="{{ route('services.index', ['facility_id' => $service->facility->facility_id]) }}" class="btn btn-sm btn-outline-primary">
                        View All Services
                    </a>
                </div>
                @endif
                @else
                <p class="text-muted text-center mb-0">No other services at this facility</p>
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
                <h5 class="modal-title">Delete Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the service "<strong>{{ $service->name }}</strong>"?</p>
                <p class="text-danger"><small>This action cannot be undone.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('services.destroy', $service) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Service</button>
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