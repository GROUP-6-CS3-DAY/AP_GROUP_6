@extends('layouts.app')

@section('title', 'Services - InnoTrack')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-cogs me-2"></i>Services
            </h1>
            <a href="{{ route('services.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-1"></i>New Service
            </a>
        </div>
    </div>
</div>

<!-- Search and Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('services.index') }}" class="row g-3">
            <div class="col-md-3">
                <label for="search" class="form-label">Search</label>
                <input type="text" class="form-control" id="search" name="search"
                    value="{{ request('search') }}" placeholder="Search services...">
            </div>
            <div class="col-md-2">
                <label for="category" class="form-label">Category</label>
                <select class="form-select" id="category" name="category">
                    <option value="">All Categories</option>
                    @foreach($categories as $key => $value)
                    <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="skill_type" class="form-label">Skill Type</label>
                <select class="form-select" id="skill_type" name="skill_type">
                    <option value="">All Types</option>
                    @foreach($skillTypes as $key => $value)
                    <option value="{{ $key }}" {{ request('skill_type') == $key ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="facility_id" class="form-label">Facility</label>
                <select class="form-select" id="facility_id" name="facility_id">
                    <option value="">All Facilities</option>
                    @foreach($facilities as $facility)
                    <option value="{{ $facility->id }}" {{ request('facility_id') == $facility->id ? 'selected' : '' }}>
                        {{ $facility->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-grid">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-search me-1"></i>Filter
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Services List -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-list me-2"></i>Services List
            <span class="badge bg-secondary ms-2">{{ $services->total() }}</span>
        </h5>
    </div>
    <div class="card-body">
        @if($services->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Skill Type</th>
                        <th>Facility</th>
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
                            <span class="badge bg-info">{{ $categories[$service->category] ?? $service->category }}</span>
                        </td>
                        <td>
                            <span class="badge bg-warning">{{ $skillTypes[$service->skill_type] ?? $service->skill_type }}</span>
                        </td>
                        <td>
                            <a href="{{ route('facilities.show', $service->facility) }}" class="text-decoration-none">
                                <i class="fas fa-building me-1"></i>{{ $service->facility->name }}
                            </a>
                        </td>
                        <td>{{ Str::limit($service->description, 60) }}</td>
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

        <!-- Pagination -->
        @if($services->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $services->appends(request()->query())->links() }}
        </div>
        @endif
        @else
        <div class="text-center py-5">
            <i class="fas fa-cogs fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No services found</h5>
            <p class="text-muted">
                @if(request()->has('search') || request()->has('category') || request()->has('skill_type') || request()->has('facility_id'))
                Try adjusting your search criteria or
                <a href="{{ route('services.index') }}">clear all filters</a>.
                @else
                Get started by creating your first service.
                @endif
            </p>
            @if(!request()->has('search') && !request()->has('category') && !request()->has('skill_type') && !request()->has('facility_id'))
            <a href="{{ route('services.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-1"></i>Create First Service
            </a>
            @endif
        </div>
        @endif
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
</style>
@endpush

@push('scripts')
<script>
    // Auto-submit form when filters change
    document.getElementById('category').addEventListener('change', function() {
        this.form.submit();
    });

    document.getElementById('skill_type').addEventListener('change', function() {
        this.form.submit();
    });

    document.getElementById('facility_id').addEventListener('change', function() {
        this.form.submit();
    });
</script>
@endpush