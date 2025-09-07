@extends('layouts.app')

@section('title', 'Project Details - InnoTrack')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0"><i class="fas fa-project-diagram me-2"></i>Project Details</h1>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('projects.edit', $project) }}" class="btn btn-outline-warning"><i class="fas fa-edit me-1"></i>Edit</a>
                <form action="{{ route('projects.destroy', $project) }}" method="POST" onsubmit="return confirm('Delete this project?');">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger" type="submit"><i class="fas fa-trash me-1"></i>Delete</button>
                </form>
                <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Back</a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-body">
                <h4 class="mb-2">{{ $project->title }}</h4>
                <p class="text-muted mb-4">{{ $project->description }}</p>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="p-3 border rounded bg-light h-100">
                            <small class="text-muted d-block mb-1">Program</small>
                            @if($project->program)
                                <a href="{{ route('programs.show', $project->program) }}" class="text-decoration-none"><i class="fas fa-th-large me-1"></i>{{ $project->program->name }}</a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 border rounded bg-light h-100">
                            <small class="text-muted d-block mb-1">Facility</small>
                            @if($project->facility)
                                <a href="{{ route('facilities.show', $project->facility) }}" class="text-decoration-none"><i class="fas fa-building me-1"></i>{{ $project->facility->name }}</a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 border rounded bg-light h-100">
                            <small class="text-muted d-block mb-1">Innovation Focus</small>
                            <span class="badge bg-info">{{ $innovationFocus[$project->innovation_focus] ?? $project->innovation_focus }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 border rounded bg-light h-100">
                            <small class="text-muted d-block mb-1">Prototype Stage</small>
                            <span class="badge bg-warning">{{ $prototypeStages[$project->prototype_stage] ?? $project->prototype_stage }}</span>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="text-uppercase text-muted fw-semibold mb-2">Nature of Project</h6>
                    <p class="text-muted mb-0">{{ $project->nature_of_project }}</p>
                </div>

                <div class="mb-4">
                    <h6 class="text-uppercase text-muted fw-semibold mb-2">Testing Requirements</h6>
                    <p class="text-muted mb-0">{{ $project->testing_requirements }}</p>
                </div>

                <div class="mb-4">
                    <h6 class="text-uppercase text-muted fw-semibold mb-2">Commercialization Plan</h6>
                    <p class="text-muted mb-0">{{ $project->commercialization_plan }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header py-2 d-flex justify-content-between align-items-center"><span class="fw-semibold"><i class="fas fa-info-circle me-2"></i>Meta</span></div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted d-block">Created</small>
                    <span>{{ $project->created_at->diffForHumans() }}</span><br>
                    <small class="text-muted">{{ $project->created_at->format('Y-m-d H:i') }}</small>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Last Updated</small>
                    <span>{{ $project->updated_at->diffForHumans() }}</span><br>
                    <small class="text-muted">{{ $project->updated_at->format('Y-m-d H:i') }}</small>
                </div>
                <hr>
                <!-- Participants temporarily disabled -->
                <div class="text-center">
                    <h4 class="text-primary mb-0">—</h4>
                    <small class="text-muted">Participants (disabled)</small>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h6 class="text-uppercase text-muted fw-semibold mb-3">Quick Actions</h6>
                <div class="d-grid gap-2">
                    <a href="{{ route('projects.edit', $project) }}" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit me-1"></i>Edit Project</a>
                    <a href="{{ route('projects.index') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-list me-1"></i>All Projects</a>
                    <!-- Participant add disabled -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>.badge{font-size:.7rem;letter-spacing:.5px}</style>
@endpush