@extends('layouts.app')

@section('title', 'Programs - InnoTrack')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-layer-group me-2"></i>Programs
            </h1>
            <a href="{{ route('programs.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-1"></i>New Program
            </a>
        </div>
    </div>
</div>

<!-- Search / Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('programs.index') }}" class="row g-3">
            <div class="col-md-6">
                <label for="search" class="form-label">Search</label>
                <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Search programs by name or description...">
            </div>
            <div class="col-md-3 align-self-end">
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
            <span class="badge bg-secondary ms-2">{{ method_exists($programs, 'total') ? $programs->total() : $programs->count() }}</span>
        </h5>
    </div>
    <div class="card-body">
        @if($programs->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>National Alignment</th>
                        <th>Focus Areas</th>
                        <th>Phases</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($programs as $program)
                    <tr>
                        <td>{{ $program->id }}</td>
                        <td>
                            <strong>{{ $program->name }}</strong>
                            <br><small class="text-muted">{{ Str::limit($program->description, 60) }}</small>
                        </td>
                        <td>
                            <span class="text-muted">{{ $program->national_alignment ?? '-' }}</span>
                        </td>
                        <td>
                            @if(is_array($program->focus_areas) || $program->focus_areas)
                                @php
                                    $areas = is_array($program->focus_areas) ? $program->focus_areas : explode(',', $program->focus_areas);
                                @endphp
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach(array_slice($areas, 0, 3) as $area)
                                    <span class="badge bg-light text-dark">{{ trim($area) }}</span>
                                    @endforeach
                                    @if(count($areas) > 3)
                                    <span class="badge bg-secondary">+{{ count($areas) - 3 }}</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info text-dark">{{ $program->phases ?? '-' }}</span>
                        </td>
                        <td class="text-end">
                            <div class="btn-group" role="group">
                                <a href="{{ route('programs.show', $program) }}" class="btn btn-sm btn-outline-primary" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('programs.edit', $program) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('programs.destroy', $program) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this program?')">
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
        @if(method_exists($programs, 'hasPages') && $programs->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $programs->appends(request()->query())->links() }}
        </div>
        @endif

        @else
        <div class="text-center py-5">
            <i class="fas fa-layer-group fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No programs found</h5>
            <p class="text-muted">
                @if(request()->has('search'))
                Try adjusting your search criteria or <a href="{{ route('programs.index') }}">clear all filters</a>.
                @else
                Get started by creating your first program.
                @endif
            </p>
            @if(!request()->has('search'))
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
    .table th { border-top: none; font-weight: 600; }
    .btn-group .btn { margin-right: 2px; }
    .btn-group .btn:last-child { margin-right: 0; }
    .badge { font-size: 0.75em; }
</style>
@endpush

@push('scripts')
<script>
    // simple helper to submit on enter in search
    document.getElementById('search')?.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            this.form.submit();
        }
    });
</script>
@endpush