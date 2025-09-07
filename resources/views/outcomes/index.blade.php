@extends('layouts.app')

@section('title', 'Outcomes - InnoTrack')

@section('content')
@php
use Illuminate\Support\Str;
@endphp
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-trophy me-2"></i>Outcomes
            </h1>
            <a href="{{ route('outcomes.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-1"></i>New Outcome
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-list me-2"></i>Outcome List
            <span class="badge bg-secondary ms-2">{{ $outcomes->count() }}</span>
        </h5>
    </div>
    <div class="card-body">
        @if($outcomes->count())
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>Project</th>
                        <th>Type</th>
                        <th>Impact</th>
                        <th>Date Achieved</th>
                        <th>Commercialization</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($outcomes as $outcome)
                    <tr>
                        <td>
                            <strong>{{ $outcome->title }}</strong><br>
                            <small class="text-muted">{{ Str::limit($outcome->description, 50) }}</small>
                        </td>
                        <td>
                            @if($outcome->project)
                            <a href="{{ route('projects.show', $outcome->project_id ?? $outcome->project_ID) }}" class="text-decoration-none">
                                <i class="fas fa-project-diagram me-1"></i>{{ $outcome->project->title ?? 'Project' }}
                            </a>
                            @else
                            <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td><span class="badge bg-info">{{ $outcome->outcome_type }}</span></td>
                        <td><span class="badge bg-primary">{{ $outcome->impact }}</span></td>
                        <td>{{ $outcome->date_achieved ? \Carbon\Carbon::parse($outcome->date_achieved)->format('Y-m-d') : '—' }}</td>
                        <td><span class="badge bg-warning">{{ $outcome->commercialization_status }}</span></td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('outcomes.show', $outcome->id ?? $outcome->outcome_ID) }}" class="btn btn-sm btn-outline-primary" title="View"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('outcomes.edit', $outcome->id ?? $outcome->outcome_ID) }}" class="btn btn-sm btn-outline-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('outcomes.destroy', $outcome->id ?? $outcome->outcome_ID) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this outcome?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
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
            <i class="fas fa-trophy fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No outcomes recorded</h5>
            <p class="text-muted mb-3">Track innovation outputs such as prototypes, certifications, IP, and commercialization milestones.</p>
            <a href="{{ route('outcomes.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-1"></i>Create First Outcome
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    .badge { font-size: 0.7rem; }
</style>
@endpush