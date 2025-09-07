@extends('layouts.app')

@section('title', 'Outcome Details - InnoTrack')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0"><i class="fas fa-trophy me-2"></i>Outcome Details</h1>
            <div>
                <a href="{{ route('outcomes.edit', $outcome->id ?? $outcome->outcome_ID) }}" class="btn btn-outline-warning me-2"><i class="fas fa-edit me-1"></i>Edit</a>
                <form action="{{ route('outcomes.destroy', $outcome->id ?? $outcome->outcome_ID) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this outcome?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger" type="submit"><i class="fas fa-trash me-1"></i>Delete</button>
                </form>
                <a href="{{ route('outcomes.index') }}" class="btn btn-outline-secondary ms-2"><i class="fas fa-arrow-left me-1"></i>Back</a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <h4 class="mb-1">{{ $outcome->title }}</h4>
                <p class="text-muted mb-3">{{ $outcome->description }}</p>

                <div class="mb-3">
                    <strong>Project: </strong>
                    @if($outcome->project)
                    <a href="{{ route('projects.show', $outcome->project_id ?? $outcome->project_ID) }}" class="text-decoration-none"><i class="fas fa-project-diagram me-1"></i>{{ $outcome->project->title }}</a>
                    @else
                    <span class="text-muted">—</span>
                    @endif
                </div>

                <div class="mb-3">
                    <strong>Outcome Type: </strong>
                    <span class="badge bg-info">{{ $outcome->outcome_type }}</span>
                </div>

                <div class="mb-3">
                    <strong>Quality / Certification: </strong>
                    <span class="badge bg-primary">{{ $outcome->quality_certification }}</span>
                </div>

                <div class="mb-3">
                    <strong>Commercialization Status: </strong>
                    <span class="badge bg-warning">{{ $outcome->commercialization_status }}</span>
                </div>

                <div class="mb-3">
                    <strong>Impact: </strong>
                    <span class="badge bg-success">{{ $outcome->impact }}</span>
                </div>

                <div class="mb-3">
                    <strong>Date Achieved: </strong>
                    {{ $outcome->date_achieved ? \Carbon\Carbon::parse($outcome->date_achieved)->format('Y-m-d') : '—' }}
                </div>

                <div class="mb-3">
                    <strong>Artifact Link: </strong>
                    @if($outcome->artifact_link)
                    <a href="{{ $outcome->artifact_link }}" target="_blank" rel="noopener" class="text-decoration-none"><i class="fas fa-external-link-alt me-1"></i>View Artifact</a>
                    @else
                    <span class="text-muted">—</span>
                    @endif
                </div>
            </div>

            <div class="col-md-4">
                <div class="border rounded p-3 bg-light">
                    <p class="mb-1"><small class="text-muted">Created</small></p>
                    <p class="mb-2">{{ $outcome->created_at?->diffForHumans() }}<br><small class="text-muted">{{ $outcome->created_at?->format('Y-m-d H:i') }}</small></p>

                    <p class="mb-1"><small class="text-muted">Last Updated</small></p>
                    <p class="mb-2">{{ $outcome->updated_at?->diffForHumans() }}<br><small class="text-muted">{{ $outcome->updated_at?->format('Y-m-d H:i') }}</small></p>

                    <hr>
                    <div class="text-center">
                        <h4 class="text-primary">{{ $outcome->project ? 1 : 0 }}</h4>
                        <small class="text-muted">Linked Project</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>.badge{font-size:0.8rem}</style>
@endpush