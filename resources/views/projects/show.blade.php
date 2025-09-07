@extends('layouts.app')

@section('title', 'Project Details - InnoTrack')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-project-diagram me-2"></i>Project Details
            </h1>
            <div>
                <a href="{{ route('projects.edit', $project) }}" class="btn btn-outline-warning me-2">
                    <i class="fas fa-edit me-1"></i>Edit
                </a>
                <form action="{{ route('projects.destroy', $project) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this project?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger" type="submit">
                        <i class="fas fa-trash me-1"></i>Delete
                    </button>
                </form>
                <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary ms-2">
                    <i class="fas fa-arrow-left me-1"></i>Back
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <h4 class="mb-1">{{ $project->title }}</h4>
                <p class="text-muted mb-3">{{ $project->description }}</p>

                <div class="mb-3">
                    <strong>Program: </strong>
                    @if($project->program)
                    <a href="{{ route('programs.show', $project->program) }}" class="text-decoration-none">
                        <i class="fas fa-th-large me-1"></i>{{ $project->program->name }}
                    </a>
                    @else
                    <span class="text-muted">—</span>
                    @endif
                </div>

                <div class="mb-3">
                    <strong>Facility: </strong>
                    @if($project->facility)
                    <a href="{{ route('facilities.show', $project->facility) }}" class="text-decoration-none">
                        <i class="fas fa-building me-1"></i>{{ $project->facility->name }}
                    </a>
                    @else
                    <span class="text-muted">—</span>
                    @endif
                </div>

                <div class="mb-3">
                    <strong>Innovation Focus: </strong>
                    <span class="badge bg-info">{{ $innovationFocus[$project->innovation_focus] ?? $project->innovation_focus }}</span>
                </div>

                <div class="mb-3">
                    <strong>Prototype Stage: </strong>
                    <span class="badge bg-warning">{{ $prototypeStages[$project->prototype_stage] ?? $project->prototype_stage }}</span>
                </div>

                <div class="mb-3">
                    <strong>Nature of Project: </strong>
                    <p class="text-muted">{{ $project->nature_of_project }}</p>
                </div>

                <div class="mb-3">
                    <strong>Testing Requirements: </strong>
                    <p class="text-muted">{{ $project->testing_requirements }}</p>
                </div>

                <div class="mb-3">
                    <strong>Commercialization Plan: </strong>
                    <p class="text-muted">{{ $project->commercialization_plan }}</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="border rounded p-3 bg-light">
                    <p class="mb-1"><small class="text-muted">Created</small></p>
                    <p class="mb-2">{{ $project->created_at->diffForHumans() }}<br><small class="text-muted">{{ $project->created_at->format('Y-m-d H:i') }}</small></p>

                    <p class="mb-1"><small class="text-muted">Last Updated</small></p>
                    <p class="mb-2">{{ $project->updated_at->diffForHumans() }}<br><small class="text-muted">{{ $project->updated_at->format('Y-m-d H:i') }}</small></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .badge { font-size: 0.85em; }
</style>
@endpush