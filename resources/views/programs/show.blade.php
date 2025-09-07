@extends('layouts.app')

@section('title', 'Program Details - InnoTrack')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0"><i class="fas fa-th-large me-2"></i>Program Details</h1>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('programs.edit', $program) }}" class="btn btn-outline-warning"><i class="fas fa-edit me-1"></i>Edit</a>
                <form action="{{ route('programs.destroy', $program) }}" method="POST" onsubmit="return confirm('Delete this program?');">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger" type="submit"><i class="fas fa-trash me-1"></i>Delete</button>
                </form>
                <a href="{{ route('programs.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Back</a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-body">
                <h4 class="mb-2">{{ $program->name }}</h4>
                <p class="text-muted mb-4">{{ $program->description }}</p>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="p-3 border rounded bg-light h-100">
                            <small class="text-muted d-block mb-1">National Alignment</small>
                            <span class="fw-semibold">{{ $program->national_alignment }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 border rounded bg-light h-100">
                            <small class="text-muted d-block mb-1">Focus Area</small>
                            <span class="badge bg-info">{{ $program->focus_areas }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 border rounded bg-light h-100">
                            <small class="text-muted d-block mb-1">Phase</small>
                            <span class="badge bg-warning">{{ $program->phases }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 border rounded bg-light h-100 d-flex flex-column justify-content-between">
                            <div>
                                <small class="text-muted d-block mb-1">Total Projects</small>
                                <span class="h5 mb-0 text-primary">{{ $program->projects->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <h6 class="text-uppercase text-muted fw-semibold mb-3"><i class="fas fa-project-diagram me-2"></i>Projects</h6>
                @if($program->projects->count())
                    <div class="table-responsive mb-3">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Title</th>
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
                                        <a href="{{ route('projects.show', $project) }}" class="text-decoration-none"><strong>{{ $project->title }}</strong></a><br>
                                        <small class="text-muted">{{ Str::limit($project->description, 50) }}</small>
                                    </td>
                                    <td><span class="badge bg-info">{{ $project->innovation_focus }}</span></td>
                                    <td><span class="badge bg-warning">{{ $project->prototype_stage }}</span></td>
                                    <td>
                                        @if($project->facility)
                                            <a href="{{ route('facilities.show', $project->facility) }}" class="text-decoration-none"><i class="fas fa-building me-1"></i>{{ $project->facility->name }}</a>
                                        @else <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('projects.show', $project) }}" class="btn btn-outline-primary" title="View"><i class="fas fa-eye"></i></a>
                                            <a href="{{ route('projects.edit', $project) }}" class="btn btn-outline-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('projects.destroy', $project) }}" method="POST" onsubmit="return confirm('Delete this project?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <a href="{{ route('projects.create', ['program_id' => $program->id]) }}" class="btn btn-sm btn-success"><i class="fas fa-plus me-1"></i>Add Project</a>
                @else
                    <div class="text-center py-4 border rounded">
                        <i class="fas fa-project-diagram fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-2">No projects linked to this program yet.</p>
                        <a href="{{ route('projects.create', ['program_id' => $program->id]) }}" class="btn btn-sm btn-success"><i class="fas fa-plus me-1"></i>Create First Project</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header py-2 d-flex justify-content-between align-items-center"><span class="fw-semibold"><i class="fas fa-info-circle me-2"></i>Meta</span></div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted d-block">Created</small>
                    <span>{{ $program->created_at->diffForHumans() }}</span><br>
                    <small class="text-muted">{{ $program->created_at->format('Y-m-d H:i') }}</small>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Last Updated</small>
                    <span>{{ $program->updated_at->diffForHumans() }}</span><br>
                    <small class="text-muted">{{ $program->updated_at->format('Y-m-d H:i') }}</small>
                </div>
                <hr>
                <div class="text-center">
                    <h4 class="text-primary mb-0">{{ $program->projects->count() }}</h4>
                    <small class="text-muted">Projects</small>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h6 class="text-uppercase text-muted fw-semibold mb-3">Quick Actions</h6>
                <div class="d-grid gap-2">
                    <a href="{{ route('projects.create', ['program_id' => $program->id]) }}" class="btn btn-sm btn-outline-success"><i class="fas fa-plus me-1"></i>New Project</a>
                    <a href="{{ route('programs.edit', $program) }}" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit me-1"></i>Edit Program</a>
                    <a href="{{ route('programs.index') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-list me-1"></i>All Programs</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>.badge{font-size:.7rem;letter-spacing:.5px}</style>
@endpush