@extends('layouts.app')

@section('title', 'Programs - InnoTrack')

@section('content')
@php
use Illuminate\Support\Str;
@endphp
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-th-large me-2"></i>Programs
            </h1>
            <a href="{{ route('programs.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-1"></i>New Program
            </a>
        </div>
    </div>
</div>

<!-- Search and Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('programs.index') }}" class="row g-3">
            <div class="col-md-4">
                <label for="search" class="form-label">Search</label>
                <input type="text" class="form-control" id="search" name="search"
                    value="{{ request('search') }}" placeholder="Search programs...">
            </div>
            <div class="col-md-3">
                <label for="focus_areas" class="form-label">Focus Area</label>
                <select class="form-select" id="focus_areas" name="focus_areas">
                    <option value="">All Focus Areas</option>
                    @foreach($focusAreas as $key => $value)
                    <option value="{{ $key }}" {{ request('focus_areas') == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="phases" class="form-label">Phase</label>
                <select class="form-select" id="phases" name="phases">
                    <option value="">All Phases</option>
                    @foreach($phases as $key => $value)
                    <option value="{{ $key }}" {{ request('phases') == $key ? 'selected' : '' }}>{{ $value }}</option>
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

<!-- Programs List -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-list me-2"></i>Program List
            <span class="badge bg-secondary ms-2">{{ $programs->total() }}</span>
        </h5>
    </div>
    <div class="card-body">
        @if($programs->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Alignment</th>
                        <th>Focus Area</th>
                        <th>Phase</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($programs as $program)
                    <tr>
                        <td>
                            <strong>{{ $program->name }}</strong>
                        </td>
                        <td>
                            {{ $program->national_alignment }}
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $focusAreas[$program->focus_areas] ?? $program->focus_areas }}</span>
                        </td>
                        <td>
                            <span class="badge bg-warning">{{ $phases[$program->phases] ?? $program->phases }}</span>
                        </td>
                        <td>
                            <small class="text-muted">{{ Str::limit($program->description, 80) }}</small>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('programs.show', $program) }}"
                                    class="btn btn-sm btn-outline-primary" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('programs.edit', $program) }}"
                                    class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('programs.destroy', $program) }}"
                                    method="POST" class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this program?')">
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
        @if($programs->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $programs->appends(request()->query())->links() }}
        </div>
        @endif
        @else
        <div class="text-center py-5">
            <i class="fas fa-th-large fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No programs found</h5>
            <p class="text-muted">
                @if(request()->has('search') || request()->has('focus_areas') || request()->has('phases'))
                Try adjusting your search criteria or
                <a href="{{ route('programs.index') }}">clear all filters</a>.
                @else
                Get started by creating your first program.
                @endif
            </p>
            @if(!request()->has('search') && !request()->has('focus_areas') && !request()->has('phases'))
            <a href="{{ route('programs.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-1"></i>Create First Program
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
</style>
@endpush

@push('scripts')
<script>
    // Auto-submit form when filters change
    var focusAreasEl = document.getElementById('focus_areas');
    var phasesEl = document.getElementById('phases');

    if (focusAreasEl) {
        focusAreasEl.addEventListener('change', function() { this.form.submit(); });
    }
    if (phasesEl) {
        phasesEl.addEventListener('change', function() { this.form.submit(); });
    }

    // simple helper to submit on enter in search
    document.getElementById('search')?.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            this.form.submit();
        }
    });
</script>
@endpush