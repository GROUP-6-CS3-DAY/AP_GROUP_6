@extends('layouts.app')

@section('title', $participant->full_name . ' - Participant Details - InnoTrack')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-user me-2"></i>{{ $participant->full_name }}
            </h1>
            <div>
                <a href="{{ route('participants.edit', $participant) }}" class="btn btn-warning me-2">
                    <i class="fas fa-edit me-1"></i>Edit Participant
                </a>
                <a href="{{ route('participants.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back to Participants
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Participant Details -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Participant Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Full Name</h6>
                        <p class="h5">{{ $participant->full_name }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Email</h6>
                        <p class="mb-0">
                            <i class="fas fa-envelope me-1"></i>{{ $participant->email }}
                        </p>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Affiliation</h6>
                        <span class="badge bg-secondary fs-6">{{ ucfirst($participant->affiliation) }}</span>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Specialization</h6>
                        <span class="badge bg-info fs-6">{{ ucfirst($participant->specialization) }}</span>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Institution</h6>
                        <p class="mb-0">{{ strtoupper($participant->institution) }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Cross Skill Trained</h6>
                        <span class="badge {{ $participant->cross_skill_trained ? 'bg-success' : 'bg-secondary' }}">
                            {{ $participant->cross_skill_trained ? 'Yes' : 'No' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Project Section -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-project-diagram me-2"></i>Current Project
                    <span class="badge bg-info ms-2">{{ $participant->project ? 1 : 0 }}</span>
                </h5>
                @if(!$participant->project && $availableProjects->count() > 0)
                <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#assignProjectModal">
                    <i class="fas fa-plus me-1"></i>Assign Project
                </button>
                @endif
            </div>
            <div class="card-body">
                @if($participant->project)
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Project Name</th>
                                <th>Status</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <a href="{{ route('projects.show', $participant->project) }}" class="text-decoration-none">
                                        {{ $participant->project->title }}
                                    </a>
                                </td>
                                <td>
                                    <span class="badge bg-success">{{ ucfirst($participant->project->status ?? 'Active') }}</span>
                                </td>
                                <td>
                                    {{ Str::limit($participant->project->description ?? 'N/A', 50) }}
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('projects.show', $participant->project) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('participants.remove-project', [$participant->participant_id, $participant->project->project_id]) }}" 
                                              method="POST" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to remove this participant from the project?')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-3">
                    <i class="fas fa-project-diagram fa-2x text-muted mb-2"></i>
                    <p class="text-muted mb-0">No project assigned to this participant</p>
                    @if($availableProjects->count() > 0)
                    <button class="btn btn-success btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#assignProjectModal">
                        <i class="fas fa-plus me-1"></i>Assign to Project
                    </button>
                    @else
                    <p class="text-muted mt-2"><small>No available projects to assign.</small></p>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('participants.edit', $participant) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i>Edit Participant
                    </a>
                    @if(!$participant->project && $availableProjects->count() > 0)
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#assignProjectModal">
                        <i class="fas fa-plus me-1"></i>Assign Project
                    </button>
                    @endif
                    @if($participant->project)
                    <form action="{{ route('participants.remove-project', [$participant->participant_id, $participant->project->project_id]) }}" 
                          method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to remove this participant from the project?')">
                            <i class="fas fa-times me-1"></i>Remove from Project
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>

       

        <!-- Skills & Training -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-graduation-cap me-2"></i>Skills & Training
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted">Specialization</small>
                    <div>
                        <span class="badge bg-primary">{{ ucfirst($participant->specialization) }}</span>
                    </div>
                </div>
                <div class="mb-3">
                    <small class="text-muted">Cross Skill Training</small>
                    <div>
                        <span class="badge {{ $participant->cross_skill_trained ? 'bg-success' : 'bg-secondary' }}">
                            {{ $participant->cross_skill_trained ? 'Completed' : 'Not Completed' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Assign Project Modal -->
@if(!$participant->project && $availableProjects->count() > 0)
<div class="modal fade" id="assignProjectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign to Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('participants.add-project', $participant->participant_id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Project</label>
                        <select name="project_id" class="form-select" required>
                            <option value="">Choose a project...</option>
                            @foreach($availableProjects as $project)
                                <option value="{{ $project->project_id }}">{{ $project->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="alert alert-info">
                        <small>This will assign {{ $participant->full_name }} to the selected project.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Assign to Project</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Participant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the participant "<strong>{{ $participant->full_name }}</strong>"?</p>
                <p class="text-danger"><small>This action cannot be undone.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('participants.destroy', $participant) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Participant</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Delete confirmation
    function confirmDelete() {
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }
</script>
@endpush

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endsection