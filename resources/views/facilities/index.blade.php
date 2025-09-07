@extends('layouts.app')

@section('title', 'Facilities - InnoTrack')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-building me-2"></i>Facilities
            </h1>
            <a href="{{ route('facilities.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>New Facility
            </a>
        </div>
    </div>
</div>

<!-- Search and Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('facilities.index') }}" class="row g-3">
            <div class="col-md-4">
                <label for="search" class="form-label">Search</label>
                <input type="text" class="form-control" id="search" name="search"
                    value="{{ request('search') }}" placeholder="Search facilities...">
            </div>
            <div class="col-md-3">
                <label for="facility_type" class="form-label">Facility Type</label>
                <select class="form-select" id="facility_type" name="facility_type">
                    <option value="">All Types</option>
                    @foreach($facilityTypes as $key => $value)
                    <option value="{{ $key }}" {{ request('facility_type') == $key ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="partner_organization" class="form-label">Partner Organization</label>
                <input type="text" class="form-control" id="partner_organization" name="partner_organization"
                    value="{{ request('partner_organization') }}" placeholder="e.g., UniPod, UIRI">
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

<!-- Facilities List -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-list me-2"></i>Facilities List
            <span class="badge bg-secondary ms-2">{{ $facilities->total() }}</span>
        </h5>
    </div>
    <div class="card-body">
        @if($facilities->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Location</th>
                        <th>Partner Organization</th>
                        <th>Services</th>
                        <th>Equipment</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($facilities as $facility)
                    <tr>
                        <td>
                            <strong>{{ $facility->name }}</strong>
                            <br>
                            <small class="text-muted">{{ Str::limit($facility->description, 60) }}</small>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $facilityTypes[$facility->facility_type] ?? $facility->facility_type }}</span>
                        </td>
                        <td>{{ Str::limit($facility->location, 40) }}</td>
                        <td>
                            <span class="badge bg-secondary">{{ $facility->partner_organization }}</span>
                        </td>
                        <td>
                            <span class="badge bg-success">{{ $facility->services_count ?? $facility->services->count() }}</span>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $facility->equipment_count ?? $facility->equipment->count() }}</span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('facilities.show', $facility) }}"
                                    class="btn btn-sm btn-outline-primary" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('facilities.edit', $facility) }}"
                                    class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('facilities.destroy', $facility) }}"
                                    method="POST" class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this facility?')">
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
        @if($facilities->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $facilities->appends(request()->query())->links() }}
        </div>
        @endif
        @else
        <div class="text-center py-5">
            <i class="fas fa-building fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No facilities found</h5>
            <p class="text-muted">
                @if(request()->has('search') || request()->has('facility_type') || request()->has('partner_organization'))
                Try adjusting your search criteria or
                <a href="{{ route('facilities.index') }}">clear all filters</a>.
                @else
                Get started by creating your first facility.
                @endif
            </p>
            @if(!request()->has('search') && !request()->has('facility_type') && !request()->has('partner_organization'))
            <a href="{{ route('facilities.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>Create First Facility
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
    document.getElementById('facility_type').addEventListener('change', function() {
        this.form.submit();
    });

    // Clear filters
    function clearFilters() {
        window.location.href = '{{ route("facilities.index") }}';
    }
</script>
@endpush