@extends('layouts.app')

@section('title', 'Projects - InnoTrack')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-project-diagram me-2"></i>Projects
            </h1>
            <a href="{{ route('projects.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-1"></i>New Project
            </a>
        </div>
    </div>
</div>

<!-- Search and Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('projects.index') }}" class="row g-3">
            <div class="col-md-3">
                <label for="search" class="form-label">Search</label>
                <input type="text" class="form-control" id="search" name="search"
                    value="{{ request('search') }}" placeholder="Search projects...">
            </div>
            <div class="col-md-2">
                <label for="innovation_focus" class="form-label">Innovation Focus</label>
                <select class="form-select" id="innovation_focus" name="innovation_focus">
                    <option value="">All Focus Areas</option>
                    @foreach($innovationFocus as $key => $value)
                    <option value="{{ $key }}" {{ request('innovation_focus') == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="prototype_stage" class="form-label">Prototype Stage</label>
                <select class="form-select" id="prototype_stage" name="prototype_stage">
                    <option value="">All Stages</option>
                    @foreach($prototypeStages as $key => $value)
                    <option value="{{ $key }}" {{ request('prototype_stage') == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="program_id" class="form-label">Program</label>
                <select class="form-select" id="program_id" name="program_id">
                    <option value="">All Programs</option>
                    @foreach($programs as $program)
                    <option value="{{ $program->id }}" {{ request('program_id') == $program->id ? 'selected' : '' }}>{{ $program->name }}</option>
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

<!-- Projects List -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-list me-2"></i>Project List
            <span class="badge bg-secondary ms-2">{{ $projects->total() }}</span>
        </h5>
    </div>
    <div class="card-body">
        @if($projects->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>Program</th>
                        <th>Innovation Focus</th>
                        <th>Prototype Stage</th>
                        <th>Facility</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($projects as $project)
                    <tr>
                        <td>
                            <strong>{{ $project->title }}</strong>
                            <br><small class="text-muted">{{ Str::limit($project->description, 40) }}</small>
                        </td>
                        <td>
                            @if($project->program)
                            <a href="{{ route('programs.show', $project->program) }}" class="text-decoration-none">
                                <i class="fas fa-th-large me-1"></i>{{ $project->program->name }}
                            </a>
                            @else
                            <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $innovationFocus[$project->innovation_focus] ?? $project->innovation_focus }}</span>
                        </td>
                        <td>
                            <span class="badge bg-warning">{{ $prototypeStages[$project->prototype_stage] ?? $project->prototype_stage }}</span>
                        </td>
                        <td>
                            @if($project->facility)
                            <a href="{{ route('facilities.show', $project->facility) }}" class="text-decoration-none">
                                <i class="fas fa-building me-1"></i>{{ $project->facility->name }}
                            </a>
                            @else
                            <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('projects.show', $project) }}"
                                    class="btn btn-sm btn-outline-primary" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('projects.edit', $project) }}"
                                    class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('projects.destroy', $project) }}"
                                    method="POST" class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this project?')">
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
        @if($projects->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $projects->appends(request()->query())->links() }}
        </div>
        @endif
        @else
        <div class="text-center py-5">
            <i class="fas fa-project-diagram fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No projects found</h5>
            <p class="text-muted">
                @if(request()->has('search') || request()->has('innovation_focus') || request()->has('prototype_stage') || request()->has('program_id'))
                Try adjusting your search criteria or
                <a href="{{ route('projects.index') }}">clear all filters</a>.
                @else
                Get started by creating your first project.
                @endif
            </p>
            @if(!request()->has('search') && !request()->has('innovation_focus') && !request()->has('prototype_stage') && !request()->has('program_id'))
            <a href="{{ route('projects.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-1"></i>Create First Project
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
    var innovationFocusEl = document.getElementById('innovation_focus');
    var prototypeStageEl = document.getElementById('prototype_stage');
    var programEl = document.getElementById('program_id');

    if (innovationFocusEl) {
        innovationFocusEl.addEventListener('change', function() { this.form.submit(); });
    }
    if (prototypeStageEl) {
        prototypeStageEl.addEventListener('change', function() { this.form.submit(); });
    }
    if (programEl) {
        programEl.addEventListener('change', function() { this.form.submit(); });
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