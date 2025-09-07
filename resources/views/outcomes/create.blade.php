@extends('layouts.app')

@section('title', 'Create Outcome - InnoTrack')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-plus-circle me-2"></i>Create Outcome
            </h1>
            <a href="{{ route('outcomes.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Outcomes
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('outcomes.store') }}" method="POST">
            @csrf

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label" for="title">Title</label>
                    <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="project_id">Project</label>
                    <select id="project_id" name="project_id" class="form-select @error('project_id') is-invalid @enderror" required>
                        <option value="">Select project</option>
                        @foreach($projects as $project)
                        <option value="{{ $project->project_id }}" {{ old('project_id') == $project->project_id ? 'selected' : '' }}>{{ $project->title }}</option>
                        @endforeach
                    </select>
                    @error('project_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label" for="description">Description</label>
                <textarea id="description" name="description" rows="4" class="form-control @error('description') is-invalid @enderror" required>{{ old('description') }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label" for="outcome_type">Outcome Type</label>
                    <input type="text" id="outcome_type" name="outcome_type" class="form-control @error('outcome_type') is-invalid @enderror" value="{{ old('outcome_type') }}" placeholder="Prototype, Patent, Certification" required>
                    @error('outcome_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="quality_certification">Quality / Certification</label>
                    <input type="text" id="quality_certification" name="quality_certification" class="form-control @error('quality_certification') is-invalid @enderror" value="{{ old('quality_certification') }}" placeholder="e.g., ISO 9001" required>
                    @error('quality_certification')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="date_achieved">Date Achieved</label>
                    <input type="date" id="date_achieved" name="date_achieved" class="form-control @error('date_achieved') is-invalid @enderror" value="{{ old('date_achieved') }}" required>
                    @error('date_achieved')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label" for="commercialization_status">Commercialization Status</label>
                    <input type="text" id="commercialization_status" name="commercialization_status" class="form-control @error('commercialization_status') is-invalid @enderror" value="{{ old('commercialization_status') }}" placeholder="Pilot, In Market, Licensed" required>
                    @error('commercialization_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="impact">Impact</label>
                    <input type="text" id="impact" name="impact" class="form-control @error('impact') is-invalid @enderror" value="{{ old('impact') }}" placeholder="e.g., 500 users, 20% efficiency" required>
                    @error('impact')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label" for="artifact_link">Artifact Link</label>
                <input type="url" id="artifact_link" name="artifact_link" class="form-control @error('artifact_link') is-invalid @enderror" value="{{ old('artifact_link') }}" placeholder="https://..." required>
                @error('artifact_link')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-1"></i>Create Outcome
                </button>
                <a href="{{ route('outcomes.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>.form-label{font-weight:600}</style>
@endpush