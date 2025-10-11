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
            <a href="{{ route('equipment.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>Add Equipment
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
                    <option value="{{ $facility->getId() }}" {{ request('facility_id') == $facility->getId() ? 'selected' : '' }}>
                        {{ $facility->getName() }}
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
        <div class="row">
            @foreach($equipment as $item)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title">{{ $item->getName() }}</h5>
                            <span class="badge bg-primary">{{ $item->getInventoryCode() }}</span>
                        </div>
                        <p class="card-text">{{ Str::limit($item->getDescription(), 100) }}</p>
                        <div class="mb-2">
                            <span class="badge bg-info">{{ $item->getUsageDomain()->getDisplayName() }}</span>
                            <span class="badge bg-secondary">{{ $item->getSupportPhase()->getDisplayName() }}</span>
                        </div>
                        <div class="text-muted small">
                            <strong>Capabilities:</strong>
                            <div>{{ Str::limit($item->getCapabilitiesAsString(), 80) }}</div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <div class="btn-group w-100" role="group">
                            <a href="{{ route('equipment.show', $item->getId()) }}" class="btn btn-outline-primary btn-sm">View</a>
                            <a href="{{ route('equipment.edit', $item->getId()) }}" class="btn btn-outline-secondary btn-sm">Edit</a>
                            <form action="{{ route('equipment.destroy', $item->getId()) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
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
                @if(request()->hasAny(['search', 'usage_domain', 'support_phase', 'facility_id']))
                Try adjusting your search criteria or
                <a href="{{ route('equipment.index') }}">clear all filters</a>.
                @else
                Get started by adding your first equipment.
                @endif
            </p>
            @if(!request()->hasAny(['search', 'usage_domain', 'support_phase', 'facility_id']))
            <a href="{{ route('equipment.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-1"></i>Add First Equipment
            </a>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-submit form when filters change
    document.querySelectorAll('#usage_domain, #support_phase, #facility_id').forEach(function(el) {
        el.addEventListener('change', function() { this.form.submit(); });
    });

    // Search on enter
    document.getElementById('search')?.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            this.form.submit();
        }
    });
</script>
@endpush