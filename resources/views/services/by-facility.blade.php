@extends('layouts.app')

@section('title', 'Services at ' . $facility->name . ' - InnoTrack')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-cogs me-2"></i>Services at {{ $facility->name }}
            </h1>
            <div>
                <a href="{{ route('services.create', ['facility_id' => $facility->facility_id]) }}" class="btn btn-success me-2">
                    <i class="fas fa-plus me-1"></i>Add Service
                </a>
                <a href="{{ route('facilities.show', $facility) }}" class="btn btn-outline-primary me-2">
                    <i class="fas fa-building me-1"></i>View Facility
                </a>
                <a href="{{ route('services.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>All Services
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Facility Info Card -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <h5 class="card-title">
                    <i class="fas fa-building me-2"></i>{{ $facility->name }}
                </h5>
                <p class="card-text text-muted mb-2">
                    <i class="fas fa-map-marker-alt me-1"></i>{{ $facility->location }}
                </p>
                <p class="card-text">{{ $facility->description }}</p>
            </div>
            <div class="col-md-4 text-md-end">
                <span class="badge bg-secondary fs-6">{{ $facility->getFacilityTypeOptions()[$facility->facility_type] ?? $facility->facility_type }}</span>
                <p class="text-muted mt-2 mb-0">
                    <strong>Partner:</strong> {{ $facility->partner_organization }}
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Services List -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-list me-2"></i>Available Services
            <span class="badge bg-secondary ms-2">{{ $services->count() }}</span>
        </h5>
    </div>
    <div class="card-body">
        @if($services->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Service Name</th>
                        <th>Category</th>
                        <th>Skill Type</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($services as $service)
                    <tr>
                        <td>
                            <strong>{{ $service->name }}</strong>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $service->getCategoryOptions()[$service->category] ?? $service->category }}</span>
                        </td>
                        <td>
                            <span class="badge bg-warning">{{ $service->getSkillTypeOptions()[$service->skill_type] ?? $service->skill_type }}</span>
                        </td>
                        <td>{{ Str::limit($service->description, 80) }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('services.show', $service) }}"
                                    class="btn btn-sm btn-outline-primary" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('services.edit', $service) }}"
                                    class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('services.destroy', $service) }}"
                                    method="POST" class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this service?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-cogs fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No services available</h5>
            <p class="text-muted">This facility doesn't have any services registered yet.</p>
            <a href="{{ route('services.create', ['facility_id' => $facility->facility_id]) }}" class="btn btn-success">
                <i class="fas fa-plus me-1"></i>Add First Service
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Quick Stats -->
<div class="row mt-4">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-cogs fa-2x text-primary mb-2"></i>
                <h4 class="text-primary">{{ $services->count() }}</h4>
                <p class="text-muted mb-0">Total Services</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-tools fa-2x text-success mb-2"></i>
                <h4 class="text-success">{{ $facility->equipment->count() }}</h4>
                <p class="text-muted mb-0">Total Equipment</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-project-diagram fa-2x text-info mb-2"></i>
                <h4 class="text-info">{{ $facility->projects->count() }}</h4>
                <p class="text-muted mb-0">Active Projects</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table th {
        border-top: none;
        font-weight: 600;
    }

    .btn-group .btn {
        margin-right: 2px;
    }

    .btn-group .btn:last-child {
        margin-right: 0;
    }

    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
</style>
@endpush