@extends('layouts.app')

@section('title', 'Outcomes - InnoTrack')

@section('content')
@php
use Illuminate\Support\Str;
@endphp
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-trophy me-2"></i>Outcomes
            </h1>
            <a href="{{ route('outcomes.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>Add Outcome
            </a>
        </div>
    </div>
</div>

<!-- Search and Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('outcomes.index') }}" class="row g-3">
            <div class="col-md-3">
                <label for="search" class="form-label">Search</label>
                <input type="text" class="form-control" id="search" name="search"
                    value="{{ request('search') }}" placeholder="Search outcomes...">
            </div>
            <div class="col-md-2">
                <label for="outcome_type" class="form-label">Type</label>
                <select class="form-select" id="outcome_type" name="outcome_type">
                    <option value="">All Types</option>
                    <option value="publication" {{ request('outcome_type') == 'publication' ? 'selected' : '' }}>Publication</option>
                    <option value="patent" {{ request('outcome_type') == 'patent' ? 'selected' : '' }}>Patent</option>
                    <option value="product" {{ request('outcome_type') == 'product' ? 'selected' : '' }}>Product</option>
                    <option value="prototype" {{ request('outcome_type') == 'prototype' ? 'selected' : '' }}>Prototype</option>
                    <option value="certification" {{ request('outcome_type') == 'certification' ? 'selected' : '' }}>Certification</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="commercialization_status" class="form-label">Status</label>
                <select class="form-select" id="commercialization_status" name="commercialization_status">
                    <option value="">All Statuses</option>
                    <option value="Ready" {{ request('commercialization_status') == 'Ready' ? 'selected' : '' }}>Ready</option>
                    <option value="In Progress" {{ request('commercialization_status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="Commercialized" {{ request('commercialization_status') == 'Commercialized' ? 'selected' : '' }}>Commercialized</option>
                    <option value="Not Applicable" {{ request('commercialization_status') == 'Not Applicable' ? 'selected' : '' }}>Not Applicable</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="project_id" class="form-label">Project</label>
                <select class="form-select" id="project_id" name="project_id">
                    <option value="">All Projects</option>
                    @foreach($projects as $project)
                    <option value="{{ $project->getId() }}" {{ request('project_id') == $project->getId() ? 'selected' : '' }}>
                        {{ $project->getTitle() }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1">
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

<!-- Outcomes List -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-list me-2"></i>Outcome List
            <span class="badge bg-secondary ms-2">{{ $outcomes->total() }}</span>
        </h5>
    </div>
    <div class="card-body">
        @if($outcomes->count() > 0)
        <div class="row">
            @foreach($outcomes as $outcome)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title">{{ $outcome->getTitle() }}</h5>
                            @if($outcome->canBeCommercializaed())
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle me-1"></i>Commercial
                            </span>
                            @endif
                        </div>
                        <p class="card-text">{{ Str::limit($outcome->getDescription(), 100) }}</p>
                        <div class="mb-2">
                            <span class="badge bg-primary">{{ ucfirst($outcome->getOutcomeType()) }}</span>
                            @if($outcome->isHighImpact())
                            <span class="badge bg-warning">High Impact</span>
                            @endif
                        </div>
                        <div class="text-muted small">
                            <div><i class="fas fa-calendar me-1"></i>{{ $outcome->getDateAchieved()->format('M d, Y') }}</div>
                            @if($outcome->getCommercializationStatus())
                            <div><i class="fas fa-chart-line me-1"></i>{{ $outcome->getCommercializationStatus() }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <div class="btn-group w-100" role="group">
                            <a href="{{ route('outcomes.show', $outcome->getId()) }}" class="btn btn-outline-primary btn-sm">View</a>
                            <a href="{{ route('outcomes.edit', $outcome->getId()) }}" class="btn btn-outline-secondary btn-sm">Edit</a>
                            <form action="{{ route('outcomes.destroy', $outcome->getId()) }}" method="POST" class="d-inline">
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
        @if($outcomes->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $outcomes->appends(request()->query())->links() }}
        </div>
        @endif
        @else
        <div class="text-center py-5">
            <i class="fas fa-trophy fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No outcomes found</h5>
            <p class="text-muted">
                @if(request()->has('search') || request()->has('outcome_type') || request()->has('commercialization_status') || request()->has('project_id'))
                Try adjusting your search criteria or
                <a href="{{ route('outcomes.index') }}">clear all filters</a>.
                @else
                Get started by recording your first outcome.
                @endif
            </p>
            @if(!request()->hasAny(['search', 'outcome_type', 'commercialization_status', 'project_id']))
            <a href="{{ route('outcomes.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-1"></i>Record First Outcome
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
    document.querySelectorAll('#outcome_type, #commercialization_status, #project_id').forEach(function(el) {
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