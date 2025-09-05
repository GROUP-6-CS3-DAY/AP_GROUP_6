@extends('layouts.app')

@section('title', 'Program Details - InnoTrack')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-th-list me-2"></i>Program Details
            </h1>
            <div>
                <a href="{{ route('programs.edit', $program) }}" class="btn btn-outline-warning me-2">
                    <i class="fas fa-edit me-1"></i>Edit
                </a>
                <form action="{{ route('programs.destroy', $program) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this program?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger" type="submit">
                        <i class="fas fa-trash me-1"></i>Delete
                    </button>
                </form>
                <a href="{{ route('programs.index') }}" class="btn btn-outline-secondary ms-2">
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
                <h4 class="mb-1">{{ $program->name }}</h4>
                <p class="text-muted mb-3">{{ $program->description }}</p>

                <div class="mb-3">
                    <strong>National Alignment: </strong> {{ $program->national_alignment }}
                </div>

                <div class="mb-3">
                    <strong>Focus Area: </strong>
                    <span class="badge bg-info">{{ $program->focus_areas }}</span>
                </div>

                <div class="mb-3">
                    <strong>Phase: </strong>
                    <span class="badge bg-warning">{{ $program->phases }}</span>
                </div>

            </div>

            <div class="col-md-4">
                <div class="border rounded p-3 bg-light">
                    <p class="mb-1"><small class="text-muted">Created</small></p>
                    <p class="mb-2">{{ $program->created_at->diffForHumans() }}<br><small class="text-muted">{{ $program->created_at->format('Y-m-d H:i') }}</small></p>

                    <p class="mb-1"><small class="text-muted">Last Updated</small></p>
                    <p class="mb-2">{{ $program->updated_at->diffForHumans() }}<br><small class="text-muted">{{ $program->updated_at->format('Y-m-d H:i') }}</small></p>

                    <hr>
                    <div class="text-center">
                        <h4 class="text-primary">{{ $program->projects->count() }}</h4>
                        <small class="text-muted">Total Projects</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Projects Section -->
<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="fas fa-project-diagram me-2"></i>Projects
            <span class="badge bg-primary ms-2">{{ $program->projects->count() }}</span>
        </h5>
        <a href="{{ route('projects.create', ['program_id' => $program->id]) }}" class="btn btn-sm btn-success">
            <i class="fas fa-plus me-1"></i>Add Project
        </a>
    </div>
    <div class="card-body">
        @if($program->projects->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Project Title</th>
                        <th>Innovation Focus</th>
                        <th>Prototype Stage</th>
                        <th>Facility</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($program->projects as $project)
                    <tr>
                        <td>
                            <a href="{{ route('projects.show', $project) }}" class="text-decoration-none">
                                <strong>{{ $project->title }}</strong>
                            </a>
                            <br><small class="text-muted">{{ Str::limit($project->description, 50) }}</small>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $project->innovation_focus }}</span>
                        </td>
                        <td>
                            <span class="badge bg-warning">{{ $project->prototype_stage }}</span>
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
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('projects.show', $project) }}" class="btn btn-outline-primary btn-sm" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('projects.edit', $project) }}" class="btn btn-outline-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('projects.destroy', $project) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this project?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete">
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
        @else
        <div class="text-center py-5">
            <i class="fas fa-project-diagram fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No projects found</h5>
            <p class="text-muted mb-0">This program doesn't have any projects yet.</p>
            <a href="{{ route('projects.create', ['program_id' => $program->id]) }}" class="btn btn-success mt-3">
                <i class="fas fa-plus me-1"></i>Create First Project
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    .badge { font-size: 0.85em; }
</style>
@endpush