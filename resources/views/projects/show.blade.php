@extends('layouts.app')

@section('title', $project->getTitle() . ' - InnoTrack')

@section('content')
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
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Project Status</h5>
            </div>
            <div class="card-body">
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
    </div>
</div>
@endsection