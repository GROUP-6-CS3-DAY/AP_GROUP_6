@extends('layouts.app')

@section('title', 'Program Details - InnoTrack')

@section('content')
@php
use Illuminate\Support\Str;
@endphp
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0"><i class="fas fa-th-large me-2"></i>Program Details</h1>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('programs.edit', $program->getId()) }}" class="btn btn-outline-warning"><i class="fas fa-edit me-1"></i>Edit</a>
                <form action="{{ route('programs.destroy', $program->getId()) }}" method="POST" onsubmit="return confirm('Delete this program?');">
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
                <h4 class="mb-2">{{ $program->getName() }}</h4>
                <p class="text-muted mb-4">{{ $program->getDescription() }}</p>

                <div class="mb-3">
                    <strong>National Alignment: </strong> {{ $program->getNationalAlignment() }}
                </div>

                <div class="mb-3">
                    <strong>Focus Areas: </strong>
                    @foreach($program->getFocusAreas() as $area)
                        <span class="badge bg-info me-1">{{ trim($area) }}</span>
                    @endforeach
                </div>

                <div class="mb-3">
                    <strong>Phases: </strong>
                    @foreach($program->getPhases() as $phase)
                        <span class="badge bg-warning me-1">{{ is_object($phase) ? $phase->getValue() : trim($phase) }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header py-2 d-flex justify-content-between align-items-center">
                <span class="fw-semibold"><i class="fas fa-info-circle me-2"></i>Meta</span>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted d-block">Program ID</small>
                    <span><code>{{ $program->getId() }}</code></span>
                </div>
                <hr>
                <div class="text-center">
                    <h4 class="text-primary mb-0">{{ count($program->getProjects()) }}</h4>
                    <small class="text-muted">Projects</small>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body">
                <h6 class="text-uppercase text-muted fw-semibold mb-3">Quick Actions</h6>
                <div class="d-grid gap-2">
                    <a href="{{ route('projects.create', ['program_id' => $program->getId()]) }}" class="btn btn-sm btn-outline-success">
                        <i class="fas fa-plus me-1"></i>New Project
                    </a>
                    <a href="{{ route('programs.edit', $program->getId()) }}" class="btn btn-sm btn-outline-warning">
                        <i class="fas fa-edit me-1"></i>Edit Program
                    </a>
                    <a href="{{ route('programs.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-list me-1"></i>All Programs
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Projects Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-project-diagram me-2"></i>Projects
                    <span class="badge bg-primary ms-2">{{ count($program->getProjects()) }}</span>
                </h5>
                <a href="{{ route('projects.create', ['program_id' => $program->getId()]) }}" class="btn btn-sm btn-success">
                    <i class="fas fa-plus me-1"></i>Add Project
                </a>
            </div>
            <div class="card-body">
                @if(count($program->getProjects()) > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Project Title</th>
                                <th>Innovation Focus</th>
                                <th>Prototype Stage</th>
                                <th>Status</th>
                                <th>Participants</th>
                                <th>Outcomes</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($program->getProjects() as $project)
                            <tr>
                                <td>
                                    <a href="{{ route('projects.show', $project->getId()) }}" class="text-decoration-none">
                                        <strong>{{ $project->getTitle() }}</strong>
                                    </a>
                                    <br><small class="text-muted">{{ Str::limit($project->getDescription(), 50) }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $project->getInnovationFocus()->getDisplayName() }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-warning">{{ $project->getPrototypeStage()->getDisplayName() }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $project->getStatus()->getValue() === 'completed' ? 'success' : 'secondary' }}">
                                        {{ $project->getStatus()->getDisplayName() }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $project->getParticipantCount() }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-success">{{ $project->getOutcomeCount() }}</span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('projects.show', $project->getId()) }}" class="btn btn-outline-primary btn-sm" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('projects.edit', $project->getId()) }}" class="btn btn-outline-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('projects.destroy', $project->getId()) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this project?');">
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
                    <a href="{{ route('projects.create', ['program_id' => $program->getId()]) }}" class="btn btn-success mt-3">
                        <i class="fas fa-plus me-1"></i>Create First Project
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>.badge{font-size:.7rem;letter-spacing:.5px}</style>
@endpush