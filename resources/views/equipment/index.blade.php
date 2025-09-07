@extends('layouts.app')

@section('title', 'Equipment - InnoTrack')

@section('content')
@php
use Illuminate\Support\Str;
@endphp
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-tools me-2"></i>Equipment
            </h1>
            <a href="{{ route('equipment.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-1"></i>New Equipment
            </a>
        </div>
    </div>
</div>

<!-- Search and Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('equipment.index') }}" class="row g-3">
            <div class="col-md-3">
                <label for="search" class="form-label">Search</label>
                <input type="text" class="form-control" id="search" name="search"
                    value="{{ request('search') }}" placeholder="Search equipment...">
            </div>
            <div class="col-md-2">
                <label for="usage_domain" class="form-label">Usage Domain</label>
                <select class="form-select" id="usage_domain" name="usage_domain">
                    <option value="">All Domains</option>
                    @foreach($usageDomains as $key => $value)
                    <option value="{{ $key }}" {{ request('usage_domain') == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="support_phase" class="form-label">Support Phase</label>
                <select class="form-select" id="support_phase" name="support_phase">
                    <option value="">All Phases</option>
                    @foreach($supportPhases as $key => $value)
                    <option value="{{ $key }}" {{ request('support_phase') == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="facility_id" class="form-label">Facility</label>
                <select class="form-select" id="facility_id" name="facility_id">
                    <option value="">All Facilities</option>
                    @foreach($facilities as $facility)
                    <option value="{{ $facility->id }}" {{ request('facility_id') == $facility->id ? 'selected' : '' }}>{{ $facility->name }}</option>
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

<!-- Equipment List -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-list me-2"></i>Equipment List
            <span class="badge bg-secondary ms-2">{{ $equipment->total() }}</span>
        </h5>
    </div>
    <div class="card-body">
        @if($equipment->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Facility</th>
                        <th>Usage Domain</th>
                        <th>Support Phase</th>
                        <th>Inventory Code</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($equipment as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->name }}</strong>
                            <br><small class="text-muted">{{ Str::limit($item->description, 40) }}</small>
                        </td>
                        <td>
                            @if($item->facility)
                            <a href="{{ route('facilities.show', $item->facility) }}" class="text-decoration-none">
                                <i class="fas fa-building me-1"></i>{{ $item->facility->name }}
                            </a>
                            @else
                            <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $usageDomains[$item->usage_domain] ?? $item->usage_domain }}</span>
                        </td>
                        <td>
                            <span class="badge bg-warning">{{ $supportPhases[$item->support_phase] ?? $item->support_phase }}</span>
                        </td>
                        <td>
                            <code>{{ $item->inventory_code }}</code>
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

        <!-- Pagination -->
        @if($equipment->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $equipment->appends(request()->query())->links() }}
        </div>
        @endif
        @else
        <div class="text-center py-5">
            <i class="fas fa-tools fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No equipment found</h5>
            <p class="text-muted">
                @if(request()->has('search') || request()->has('usage_domain') || request()->has('support_phase') || request()->has('facility_id'))
                Try adjusting your search criteria or
                <a href="{{ route('equipment.index') }}">clear all filters</a>.
                @else
                Get started by adding your first equipment.
                @endif
            </p>
            @if(!request()->has('search') && !request()->has('usage_domain') && !request()->has('support_phase') && !request()->has('facility_id'))
            <a href="{{ route('equipment.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-1"></i>Add First Equipment
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

    .badge {
        font-size: 0.75em;
    }

    code {
        font-size: 0.85em;
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto-submit form when filters change
    var usageDomainEl = document.getElementById('usage_domain');
    var supportPhaseEl = document.getElementById('support_phase');
    var facilityEl = document.getElementById('facility_id');

    if (usageDomainEl) {
        usageDomainEl.addEventListener('change', function() { this.form.submit(); });
    }
    if (supportPhaseEl) {
        supportPhaseEl.addEventListener('change', function() { this.form.submit(); });
    }
    if (facilityEl) {
        facilityEl.addEventListener('change', function() { this.form.submit(); });
    }

    // Search on enter
    document.getElementById('search')?.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            this.form.submit();
        }
    });
</script>
@endpush