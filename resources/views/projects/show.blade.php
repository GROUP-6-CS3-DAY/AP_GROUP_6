@extends('layouts.app')

@section('title', $project->getTitle() . ' - InnoTrack')

@section('content')
@php
use Illuminate\Support\Str;
@endphp
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">{{ $project->getTitle() }}</h1>
            <div>
                <a href="{{ route('projects.edit', $project->getId()) }}" class="btn btn-primary"><i class="fas fa-edit me-1"></i>Edit</a>
                <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Back</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Project Details</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Innovation Focus:</strong>
                        <span class="badge bg-primary ms-2">{{ $project->getInnovationFocus()->getDisplayName() }}</span>
                    </div>
                    <div class="col-md-6">
                        <strong>Prototype Stage:</strong>
                        <span class="badge bg-secondary ms-2">{{ $project->getPrototypeStage()->getDisplayName() }}</span>
                    </div>
                </div>
                
                <div class="mb-3">
                    <strong>Nature of Project:</strong>
                    <p>{{ $project->getNatureOfProject() }}</p>
                </div>
                
                <div class="mb-3">
                    <strong>Description:</strong>
                    <p>{{ $project->getDescription() }}</p>
                </div>
                
                <div class="mb-3">
                    <strong>Testing Requirements:</strong>
                    <p>{{ $project->getTestingRequirements() }}</p>
                </div>
                
                <div class="mb-0">
                    <strong>Commercialization Plan:</strong>
                    <p>{{ $project->getCommercializationPlan() }}</p>
                </div>
            </div>
        </div>

        <!-- Participants Section -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-users me-2"></i>Team Members
                    <span class="badge bg-primary ms-2">{{ $project->getParticipantCount() }}</span>
                </h5>
                <a href="{{ route('participants.create', ['project_id' => $project->getId()]) }}" class="btn btn-sm btn-success">
                    <i class="fas fa-user-plus me-1"></i>Add Member
                </a>
            </div>
            <div class="card-body">
                @if($project->hasParticipants())
                <div class="row">
                    @foreach($project->getParticipants() as $participant)
                    <div class="col-md-6 mb-3">
                        <div class="card border-0 bg-light">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="card-title mb-1">
                                            <a href="{{ route('participants.show', $participant->getId()) }}" class="text-decoration-none">
                                                {{ $participant->getFullName() }}
                                            </a>
                                        </h6>
                                        <p class="card-text small text-muted mb-1">{{ $participant->getEmail() }}</p>
                                        <div>
                                            <span class="badge bg-secondary">{{ $participant->getAffiliation()->getDisplayName() }}</span>
                                            
                                            @if($participant->hasSpecialization())
                                            <span class="badge bg-info">{{ $participant->getSpecialization()->getDisplayName() }}</span>
                                            @endif
                                            
                                            @if($participant->isCrossSkillTrained())
                                            <span class="badge bg-success">Cross-Skilled</span>
                                            @endif
                                        </div>
                                    </div>
                                    <a href="{{ route('participants.show', $participant->getId()) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h6 class="text-muted">No team members assigned</h6>
                    <p class="text-muted">This project doesn't have any participants yet.</p>
                    <a href="{{ route('participants.create', ['project_id' => $project->getId()]) }}" class="btn btn-success">
                        <i class="fas fa-user-plus me-1"></i>Add First Member
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- Outcomes Section -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-trophy me-2"></i>Project Outcomes
                    <span class="badge bg-success ms-2">{{ $project->getOutcomeCount() }}</span>
                </h5>
                <a href="{{ route('outcomes.create', ['project_id' => $project->getId()]) }}" class="btn btn-sm btn-success">
                    <i class="fas fa-plus me-1"></i>Add Outcome
                </a>
            </div>
            <div class="card-body">
                @if($project->hasOutcomes())
                <div class="row">
                    @foreach($project->getOutcomes() as $outcome)
                    <div class="col-md-12 mb-3">
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="flex-grow-1">
                                        <h6 class="card-title mb-1">
                                            <a href="{{ route('outcomes.show', $outcome->getId()) }}" class="text-decoration-none">
                                                {{ $outcome->getTitle() }}
                                            </a>
                                        </h6>
                                        <p class="card-text small mb-2">{{ Str::limit($outcome->getDescription(), 100) }}</p>
                                        <div class="mb-2">
                                            <span class="badge bg-primary">{{ ucfirst($outcome->getOutcomeType()->getValue()) }}</span>
                                            
                                            @if($outcome->getCommercializationStatus()->getValue())
                                            <span class="badge bg-info">{{ $outcome->getCommercializationStatus()->getValue() }}</span>
                                            @endif
                                            
                                            @if($outcome->isHighImpact())
                                            <span class="badge bg-warning">High Impact</span>
                                            @endif
                                        </div>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ $outcome->getDateAchieved()->format('M d, Y') }}
                                        </small>
                                    </div>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('outcomes.show', $outcome->getId()) }}" class="btn btn-outline-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('outcomes.edit', $outcome->getId()) }}" class="btn btn-outline-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-trophy fa-3x text-muted mb-3"></i>
                    <h6 class="text-muted">No outcomes recorded</h6>
                    <p class="text-muted">This project doesn't have any outcomes yet.</p>
                    <a href="{{ route('outcomes.create', ['project_id' => $project->getId()]) }}" class="btn btn-success">
                        <i class="fas fa-plus me-1"></i>Record First Outcome
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Project Status</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>Status:</span>
                    <span class="badge bg-{{ $project->getStatus()->getValue() === 'completed' ? 'success' : 'primary' }}">
                        {{ $project->getStatus()->getDisplayName() }}
                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>Participants:</span>
                    <span class="badge bg-info">{{ $project->getParticipantCount() }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>Outcomes:</span>
                    <span class="badge bg-success">{{ $project->getOutcomeCount() }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>Ready for Commercialization:</span>
                    <span class="badge bg-{{ $project->isReadyForCommercialization() ? 'success' : 'warning' }}">
                        {{ $project->isReadyForCommercialization() ? 'Yes' : 'No' }}
                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span>Can Advance Stage:</span>
                    <span class="badge bg-{{ $project->canAdvanceToNextStage() ? 'success' : 'secondary' }}">
                        {{ $project->canAdvanceToNextStage() ? 'Yes' : 'No' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('participants.create', ['project_id' => $project->getId()]) }}" class="btn btn-sm btn-outline-success">
                        <i class="fas fa-user-plus me-1"></i>Add Team Member
                    </a>
                    <a href="{{ route('outcomes.create', ['project_id' => $project->getId()]) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-plus me-1"></i>Record Outcome
                    </a>
                    <a href="{{ route('projects.edit', $project->getId()) }}" class="btn btn-sm btn-outline-warning">
                        <i class="fas fa-edit me-1"></i>Edit Project
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection