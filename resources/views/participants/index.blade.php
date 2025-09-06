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
                    <option value="student" {{ request('affiliation') == 'student' ? 'selected' : '' }}>Student</option>
                    <option value="faculty" {{ request('affiliation') == 'faculty' ? 'selected' : '' }}>Faculty</option>
                    <option value="staff" {{ request('affiliation') == 'staff' ? 'selected' : '' }}>Staff</option>
                    <option value="researcher" {{ request('affiliation') == 'researcher' ? 'selected' : '' }}>Researcher</option>
                    <option value="external" {{ request('affiliation') == 'external' ? 'selected' : '' }}>External</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="specialization" class="form-label">Specialization</label>
                <select class="form-select" id="specialization" name="specialization">
                    <option value="">All Specializations</option>
                    <option value="computer_science" {{ request('specialization') == 'computer_science' ? 'selected' : '' }}>Computer Science</option>
                    <option value="engineering" {{ request('specialization') == 'engineering' ? 'selected' : '' }}>Engineering</option>
                    <option value="business" {{ request('specialization') == 'business' ? 'selected' : '' }}>Business</option>
                    <option value="design" {{ request('specialization') == 'design' ? 'selected' : '' }}>Design</option>
                    <option value="data_science" {{ request('specialization') == 'data_science' ? 'selected' : '' }}>Data Science</option>
                    <option value="other" {{ request('specialization') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="institution" class="form-label">Institution</label>
                <input type="text" class="form-control" id="institution" name="institution"
                    value="{{ request('institution') }}" placeholder="e.g., Makerere, MUST">
            </div>
            <div class="col-md-2">
                <label for="cross_skill_trained" class="form-label">Cross Skill Trained</label>
                <select class="form-select" id="cross_skill_trained" name="cross_skill_trained">
                    <option value="">All</option>
                    <option value="1" {{ request('cross_skill_trained') == '1' ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ request('cross_skill_trained') == '0' ? 'selected' : '' }}>No</option>
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

<!-- Success Message -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Participants List -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-list me-2"></i>Participants List
            <span class="badge bg-secondary ms-2">{{ $participants->count() }}</span>
        </h5>
    </div>
    <div class="card-body">
        @if($participants->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Affiliation</th>
                        <th>Specialization</th>
                        <th>Institution</th>
                        <th>Cross Skill Trained</th>
                        <th>Current Project</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($participants as $participant)
                    <tr>
                        <td>
                            <strong>{{ $participant->full_name }}</strong>
                            <br>
                            <small class="text-muted">ID: {{ $participant->participant_id }}</small>
                        </td>
                        <td>
                            <i class="fas fa-envelope me-1 text-muted"></i>{{ $participant->email }}
                        </td>
                        <td>
                            <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $participant->affiliation)) }}</span>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $participant->specialization)) }}</span>
                        </td>
                        <td>{{ strtoupper($participant->institution) }}</td>
                        <td>
                            <span class="badge {{ $participant->cross_skill_trained ? 'bg-success' : 'bg-secondary' }}">
                                {{ $participant->cross_skill_trained ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td>
                            @if($participant->project)
                                <span class="badge bg-primary">{{ $participant->project->name }}</span>
                            @else
                                <span class="text-muted">No project</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('participants.show', $participant->participant_id) }}"
                                    class="btn btn-sm btn-outline-primary" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('participants.edit', $participant->participant_id) }}"
                                    class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('participants.destroy', $participant->participant_id) }}"
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

        <!-- Pagination -->
        @if(is_object($participants) && method_exists($participants, 'hasPages') && $participants->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $participants->appends(request()->query())->links() }}
        </div>
        @endif
        @else
        <div class="text-center py-5">
            <i class="fas fa-users fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No participants found</h5>
            <p class="text-muted">
                @if(request()->has('search') || request()->has('affiliation') || request()->has('specialization') || request()->has('institution') || request()->has('cross_skill_trained'))
                Try adjusting your search criteria or
                <a href="{{ route('participants.index') }}">clear all filters</a>.
                @else
                Get started by adding your first participant.
                @endif
            </p>
            @if(!request()->has('search') && !request()->has('affiliation') && !request()->has('specialization') && !request()->has('institution') && !request()->has('cross_skill_trained'))
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

    // Clear filters
    function clearFilters() {
        window.location.href = '{{ route("participants.index") }}';
    }
</script>
@endpush