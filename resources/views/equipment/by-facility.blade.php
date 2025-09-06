@extends('layouts.app')

@section('title', 'Equipment at ' . $facility->name . ' - InnoTrack')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-tools me-2"></i>Equipment at {{ $facility->name }}
            </h1>
            <div>
                <a href="{{ route('equipment.create', ['facility_id' => $facility->facility_id]) }}" class="btn btn-success me-2">
                    <i class="fas fa-plus me-1"></i>Add Equipment
                </a>
                <a href="{{ route('facilities.show', $facility) }}" class="btn btn-outline-primary me-2">
                    <i class="fas fa-building me-1"></i>View Facility
                </a>
                <a href="{{ route('equipment.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>All Equipment
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

<!-- Equipment List -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-list me-2"></i>Available Equipment
            <span class="badge bg-secondary ms-2">{{ $equipment->count() }}</span>
        </h5>
    </div>
    <div class="card-body">
        @if($equipment->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Equipment Name</th>
                        <th>Usage Domain</th>
                        <th>Support Phase</th>
                        <th>Capabilities</th>
                        <th>Inventory Code</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($equipment as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->name }}</strong>
                            <br><small class="text-muted">{{ Str::limit($item->description, 60) }}</small>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $item->getUsageDomainOptions()[$item->usage_domain] ?? $item->usage_domain }}</span>
                        </td>
                        <td>
                            <span class="badge bg-warning">{{ $item->getSupportPhaseOptions()[$item->support_phase] ?? $item->support_phase }}</span>
                        </td>
                        <td>
                            @if($item->capabilities && count($item->capabilities) > 0)
                            <div class="d-flex flex-wrap gap-1">
                                @foreach(array_slice($item->capabilities, 0, 3) as $capability)
                                <span class="badge bg-light text-dark">{{ $capability }}</span>
                                @endforeach
                                @if(count($item->capabilities) > 3)
                                <span class="badge bg-secondary">+{{ count($item->capabilities) - 3 }}</span>
                                @endif
                            </div>
                            @else
                            <span class="text-muted">No capabilities listed</span>
                            @endif
                        </td>
                        <td>
                            <code class="small">{{ $item->inventory_code }}</code>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('equipment.show', $item) }}"
                                    class="btn btn-sm btn-outline-primary" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('equipment.edit', $item) }}"
                                    class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('equipment.destroy', $item) }}"
                                    method="POST" class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this equipment?')">
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
            <i class="fas fa-tools fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No equipment available</h5>
            <p class="text-muted">This facility doesn't have any equipment registered yet.</p>
            <a href="{{ route('equipment.create', ['facility_id' => $facility->facility_id]) }}" class="btn btn-success">
                <i class="fas fa-plus me-1"></i>Add First Equipment
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
                <i class="fas fa-tools fa-2x text-primary mb-2"></i>
                <h4 class="text-primary">{{ $equipment->count() }}</h4>
                <p class="text-muted mb-0">Total Equipment</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-cogs fa-2x text-success mb-2"></i>
                <h4 class="text-success">{{ $facility->services->count() }}</h4>
                <p class="text-muted mb-0">Total Services</p>
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

<!-- Equipment by Usage Domain -->
@if($equipment->count() > 0)
<div class="card mt-4">
    <div class="card-header">
        <h6 class="card-title mb-0">
            <i class="fas fa-chart-pie me-2"></i>Equipment by Usage Domain
        </h6>
    </div>
    <div class="card-body">
        @php
        $usageDomainStats = $equipment->groupBy('usage_domain')->map(function($group) {
        return $group->count();
        });
        @endphp

        <div class="row">
            @foreach($usageDomainStats as $domain => $count)
            <div class="col-md-3 mb-3">
                <div class="text-center">
                    <h5 class="text-primary">{{ $count }}</h5>
                    <small class="text-muted">{{ $domain }}</small>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
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

    .badge {
        font-size: 0.75em;
    }
</style>
@endpush