@extends('layouts.app')

@section('title', 'Participants - InnoTrack')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-users me-2"></i>Participants
            </h1>
            <a href="{{ route('participants.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>New Participant
            </a>
        </div>
    </div>
</div>

<!-- Search and Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('participants.index') }}" class="row g-3">
            <div class="col-md-3">
                <label for="search" class="form-label">Search</label>
                <input type="text" class="form-control" id="search" name="search"
                    value="{{ request('search') }}" placeholder="Search participants...">
            </div>
            <div class="col-md-2">
                <label for="affiliation" class="form-label">Affiliation</label>
                <select class="form-select" id="affiliation" name="affiliation">
                    <option value="">All Affiliations</option>
                    @foreach($affiliations as $key => $value)
                    <option value="{{ $key }}" {{ request('affiliation') == $key ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="specialization" class="form-label">Specialization</label>
                <select class="form-select" id="specialization" name="specialization">
                    <option value="">All Specializations</option>
                    @foreach($specializations as $key => $value)
                    <option value="{{ $key }}" {{ request('specialization') == $key ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="cross_skill_trained" class="form-label">Cross Skill Trained</label>
                <select class="form-select" id="cross_skill_trained" name="cross_skill_trained">
                    <option value="">All</option>
                    <option value="1" {{ request('cross_skill_trained') == '1' ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ request('cross_skill_trained') == '0' ? 'selected' : '' }}>No</option>
                </select>
            </div>
            <div class="col-md-2">
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

<!-- Success/Error Messages -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-dismiss="alert"></button>
</div>
@endif

<!-- Participants List -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-list me-2"></i>Participants List
            <span class="badge bg-secondary ms-2">{{ count($participants) }}</span>
        </h5>
    </div>
    <div class="card-body">
        @if(count($participants) > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Affiliation</th>
                        <th>Institution</th>
                        <th>Specialization</th>
                        <th>Cross Skill Trained</th>
                        <th>Project Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($participants as $participant)
                    <tr>
                        <td>
                            <strong>{{ $participant->getFullName() }}</strong>
                            <br>
                            <small class="text-muted">ID: {{ $participant->getId() }}</small>
                        </td>
                        <td>
                            <i class="fas fa-envelope me-1 text-muted"></i>{{ $participant->getEmail() }}
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $participant->getAffiliation()->getDisplayName() }}</span>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ strtoupper($participant->getInstitution()) }}</span>
                        </td>
                        <td>
                            @if($participant->hasSpecialization())
                                <span class="badge bg-primary">{{ $participant->getSpecialization()->getDisplayName() }}</span>
                            @else
                                <span class="text-muted">None</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $participant->isCrossSkillTrained() ? 'bg-success' : 'bg-secondary' }}">
                                {{ $participant->isCrossSkillTrained() ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td>
                            @if($participant->isAssignedToProject())
                                @php
                                    $assignedProject = collect($projects)->firstWhere(fn($p) => $p->getId() === $participant->getProjectId());
                                @endphp
                                @if($assignedProject)
                                    <span class="badge bg-success">{{ \Illuminate\Support\Str::limit($assignedProject->getTitle(), 20) }}</span>
                                @else
                                    <span class="badge bg-warning">Project Not Found</span>
                                @endif
                            @else
                                <span class="text-muted">Unassigned</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('participants.show', $participant->getId()) }}"
                                    class="btn btn-sm btn-outline-primary" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('participants.edit', $participant->getId()) }}"
                                    class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('participants.destroy', $participant->getId()) }}"
                                    method="POST" class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this participant?')">
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

        <!-- Statistics Card -->
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h4>{{ count($participants) }}</h4>
                        <small>Total Participants</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h4>{{ collect($participants)->filter(fn($p) => $p->isCrossSkillTrained())->count() }}</h4>
                        <small>Cross-Skill Trained</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <h4>{{ collect($participants)->filter(fn($p) => $p->isAssignedToProject())->count() }}</h4>
                        <small>Assigned to Projects</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <h4>{{ collect($participants)->filter(fn($p) => !$p->isAssignedToProject())->count() }}</h4>
                        <small>Unassigned</small>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-users fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No participants found</h5>
            <p class="text-muted">
                @if(request()->hasAny(['search', 'affiliation', 'specialization', 'cross_skill_trained', 'project_id']))
                Try adjusting your search criteria or
                <a href="{{ route('participants.index') }}">clear all filters</a>.
                @else
                Get started by adding your first participant.
                @endif
            </p>
            @if(!request()->hasAny(['search', 'affiliation', 'specialization', 'cross_skill_trained', 'project_id']))
            <a href="{{ route('participants.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>Add First Participant
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

    .card.bg-primary, .card.bg-success, .card.bg-info, .card.bg-warning {
        border: none;
    }

    .card.bg-primary .card-body, 
    .card.bg-success .card-body, 
    .card.bg-info .card-body, 
    .card.bg-warning .card-body {
        padding: 1rem;
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto-submit form when filters change
    document.getElementById('affiliation').addEventListener('change', function() {
        this.form.submit();
    });

    document.getElementById('specialization').addEventListener('change', function() {
        this.form.submit();
    });

    document.getElementById('cross_skill_trained').addEventListener('change', function() {
        this.form.submit();
    });

    document.getElementById('project_id').addEventListener('change', function() {
        this.form.submit();
    });

    // Clear filters
    function clearFilters() {
        window.location.href = '{{ route("participants.index") }}';
    }
</script>
@endpush