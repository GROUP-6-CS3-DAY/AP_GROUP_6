@extends('layouts.app')

@section('title', 'Outcome Details - InnoTrack')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0"><i class="fas fa-trophy me-2"></i>Outcome Details</h1>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('outcomes.edit', $outcome) }}" class="btn btn-outline-warning"><i class="fas fa-edit me-1"></i>Edit</a>
                <form action="{{ route('outcomes.destroy', $outcome) }}" method="POST" onsubmit="return confirm('Delete this outcome?');">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger" type="submit"><i class="fas fa-trash me-1"></i>Delete</button>
                </form>
                <a href="{{ route('outcomes.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Back</a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between mb-2">
                    <h4 class="mb-0">{{ $outcome->title }}</h4>
                    @if($outcome->date_achieved)
                        <span class="badge bg-secondary align-self-center"><i class="far fa-calendar-alt me-1"></i>{{ \Carbon\Carbon::parse($outcome->date_achieved)->format('Y-m-d') }}</span>
                    @endif
                </div>
                <p class="text-muted mb-4">{{ $outcome->description }}</p>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="p-3 border rounded bg-light h-100">
                            <small class="text-muted d-block mb-1">Outcome Type</small>
                            <span class="badge bg-info">{{ $outcome->outcome_type }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 border rounded bg-light h-100">
                            <small class="text-muted d-block mb-1">Quality / Certification</small>
                            <span class="badge bg-primary">{{ $outcome->quality_certification ?? '—' }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 border rounded bg-light h-100">
                            <small class="text-muted d-block mb-1">Commercialization Status</small>
                            <span class="badge bg-warning">{{ $outcome->commercialization_status ?? '—' }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 border rounded bg-light h-100">
                            <small class="text-muted d-block mb-1">Impact</small>
                            <span class="badge bg-success">{{ $outcome->impact ?? '—' }}</span>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="text-uppercase text-muted fw-semibold mb-2">Linked Project</h6>
                    @if($outcome->project)
                        <a href="{{ route('projects.show', $outcome->project_id) }}" class="text-decoration-none">
                            <i class="fas fa-project-diagram me-2"></i>{{ $outcome->project->title }}
                        </a>
                    @else
                        <span class="text-muted">No project linked</span>
                    @endif
                </div>

                <div>
                    <h6 class="text-uppercase text-muted fw-semibold mb-2">Artifact</h6>
                    @if($outcome->artifact_link)
                        <a href="{{ $outcome->artifact_link }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-external-link-alt me-1"></i>View Artifact
                        </a>
                    @else
                        <span class="text-muted">No artifact link provided</span>
                    @endif
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
                    <small class="text-muted d-block">Created</small>
                    <span>{{ $outcome->created_at?->diffForHumans() ?? '—' }}</span><br>
                    <small class="text-muted">{{ $outcome->created_at?->format('Y-m-d H:i') }}</small>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Last Updated</small>
                    <span>{{ $outcome->updated_at?->diffForHumans() ?? '—' }}</span><br>
                    <small class="text-muted">{{ $outcome->updated_at?->format('Y-m-d H:i') }}</small>
                </div>
                <hr>
                <div class="text-center">
                    <h4 class="text-primary mb-0">{{ $outcome->project ? 1 : 0 }}</h4>
                    <small class="text-muted">Linked Project</small>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h6 class="text-uppercase text-muted fw-semibold mb-3">Quick Actions</h6>
                <div class="d-grid gap-2">
                    <a href="{{ route('outcomes.edit', $outcome) }}" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit me-1"></i>Edit Outcome</a>
                    <a href="{{ route('outcomes.create') }}" class="btn btn-sm btn-outline-success"><i class="fas fa-plus me-1"></i>New Outcome</a>
                    <a href="{{ route('outcomes.index') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-list me-1"></i>All Outcomes</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .badge { font-size: .7rem; letter-spacing:.5px; }
</style>
@endpush