@extends('layouts.app')

@section('title', $participant->getFullName() . ' - Participant Details - InnoTrack')

@section('content')
@php
use Illuminate\Support\Str;
@endphp
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-user me-2"></i>{{ $participant->getFullName() }}
            </h1>
            <div>
                <a href="{{ route('participants.edit', $participant->getId()) }}" class="btn btn-warning me-2">
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
                        <p class="h5">{{ $participant->getFullName() }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Email</h6>
                        <p class="mb-0">
                            <i class="fas fa-envelope me-1"></i>{{ $participant->getEmail() }}
                            <small class="text-muted d-block">Domain: {{ $participant->getEmailDomain() }}</small>
                        </p>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Affiliation</h6>
                        <span class="badge bg-secondary fs-6">{{ $participant->getAffiliation()->getDisplayName() }}</span>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Institution</h6>
                        <span class="badge bg-info fs-6">{{ strtoupper($participant->getInstitution()) }}</span>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Specialization</h6>
                        @if($participant->hasSpecialization())
                            <span class="badge bg-primary fs-6">{{ $participant->getSpecialization()->getDisplayName() }}</span>
                        @else
                            <span class="text-muted">Not specified</span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Cross Skill Trained</h6>
                        <span class="badge {{ $participant->isCrossSkillTrained() ? 'bg-success' : 'bg-secondary' }} fs-6">
                            {{ $participant->isCrossSkillTrained() ? 'Yes' : 'No' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Project Section -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-project-diagram me-2"></i>Project Assignment
                    <span class="badge {{ $participant->isAssignedToProject() ? 'bg-success' : 'bg-secondary' }} ms-2">
                        {{ $participant->isAssignedToProject() ? 'Assigned' : 'Unassigned' }}
                    </span>
                </h5>
                @if(!$participant->isAssignedToProject() && count($availableProjects) > 0)
                <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#assignProjectModal">
                    <i class="fas fa-plus me-1"></i>Assign Project
                </button>
                @endif
            </div>
            <div class="card-body">
                @if($participant->isAssignedToProject())
                <div class="alert alert-success">
                    <h6><i class="fas fa-project-diagram me-2"></i>Currently Assigned</h6>
                    @php
                        $assignedProject = collect($availableProjects)->firstWhere(fn($p) => $p->getId() === $participant->getProjectId());
                    @endphp
                    @if($assignedProject)
                    <p class="mb-2"><strong>Project:</strong> {{ $assignedProject->getTitle() }}</p>
                    <p class="mb-2"><strong>Description:</strong> {{ Str::limit($assignedProject->getDescription(), 100) }}</p>
                    <div class="mt-3">
                        <a href="{{ route('projects.show', $assignedProject->getId()) }}" class="btn btn-outline-primary btn-sm me-2">
                            <i class="fas fa-eye me-1"></i>View Project
                        </a>
                        <form action="{{ route('participants.remove-project', ['participant' => $participant->getId(), 'project' => $assignedProject->getId()]) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm" 
                                onclick="return confirm('Are you sure you want to remove this participant from the project?')">
                                <i class="fas fa-times me-1"></i>Remove Assignment
                            </button>
                        </form>
                    </div>
                    @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Project reference exists but project not found. Please contact administrator.
                    </div>
                    @endif
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-project-diagram fa-3x text-muted mb-3"></i>
                    <h6 class="text-muted">No Project Assignment</h6>
                    <p class="text-muted">This participant is not currently assigned to any project.</p>
                    @if(count($availableProjects) > 0)
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#assignProjectModal">
                        <i class="fas fa-plus me-1"></i>Assign to Project
                    </button>
                    @else
                    <p class="text-muted"><small>No available projects for assignment.</small></p>
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
                    <a href="{{ route('participants.edit', $participant->getId()) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i>Edit Participant
                    </a>
                    @if(!$participant->isAssignedToProject() && count($availableProjects) > 0)
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#assignProjectModal">
                        <i class="fas fa-plus me-1"></i>Assign Project
                    </button>
                    @endif
                    @if($participant->isAssignedToProject())
                    @php
                        $assignedProject = collect($availableProjects)->firstWhere(fn($p) => $p->getId() === $participant->getProjectId());
                    @endphp
                    @if($assignedProject)
                    <form action="{{ route('participants.remove-project', ['participant' => $participant->getId(), 'project' => $assignedProject->getId()]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger" 
                            onclick="return confirm('Are you sure you want to remove this participant from the project?')">
                            <i class="fas fa-times me-1"></i>Remove from Project
                        </button>
                    </form>
                    @endif
                    @endif
                </div>
            </div>
        </div>

        <!-- Participant Profile -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-user-circle me-2"></i>Profile Summary
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted">Participant ID</small>
                    <div><code>{{ $participant->getId() }}</code></div>
                </div>
                <div class="mb-3">
                    <small class="text-muted">Affiliation</small>
                    <div>
                        <span class="badge bg-secondary">{{ $participant->getAffiliation()->getDisplayName() }}</span>
                    </div>
                </div>
                <div class="mb-3">
                    <small class="text-muted">Institution</small>
                    <div>
                        <span class="badge bg-info">{{ strtoupper($participant->getInstitution()) }}</span>
                    </div>
                </div>
                @if($participant->hasSpecialization())
                <div class="mb-3">
                    <small class="text-muted">Specialization</small>
                    <div>
                        <span class="badge bg-primary">{{ $participant->getSpecialization()->getDisplayName() }}</span>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Skills & Capabilities -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-graduation-cap me-2"></i>Skills & Capabilities
                </h6>
            </div>
            <div class="card-body">
                @if($participant->hasSpecialization())
                <div class="mb-3">
                    <small class="text-muted">Primary Specialization</small>
                    <div>
                        <span class="badge bg-primary">{{ $participant->getSpecialization()->getDisplayName() }}</span>
                    </div>
                </div>
                @endif
                
                <div class="mb-3">
                    <small class="text-muted">Cross-Skill Training Status</small>
                    <div>
                        <span class="badge {{ $participant->isCrossSkillTrained() ? 'bg-success' : 'bg-secondary' }}">
                            {{ $participant->isCrossSkillTrained() ? 'Completed' : 'Not Completed' }}
                        </span>
                    </div>
                    @if($participant->isCrossSkillTrained())
                    <small class="text-success"><i class="fas fa-check-circle me-1"></i>Can work across multiple domains</small>
                    @endif
                </div>

                @if($participant->hasSpecialization())
                <div class="alert alert-info">
                    <small>
                        <i class="fas fa-info-circle me-1"></i>
                        <strong>Skills Match:</strong> This participant can work on projects requiring {{ $participant->getSpecialization()->getDisplayName() }} skills{{ $participant->isCrossSkillTrained() ? ' and cross-functional work' : '' }}.
                    </small>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Assign Project Modal -->
@if(!$participant->isAssignedToProject() && count($availableProjects) > 0)
<div class="modal fade" id="assignProjectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign to Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('participants.add-project', $participant->getId()) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Project</label>
                        <select name="project_id" class="form-select" required>
                            <option value="">Choose a project...</option>
                            @foreach($availableProjects as $project)
                                <option value="{{ $project->getId() }}">{{ $project->getTitle() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="alert alert-info">
                        <small>This will assign {{ $participant->getFullName() }} to the selected project.</small>
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