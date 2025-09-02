@extends('layouts.app')

@section('title', 'Edit Outcome')

@section('content')
    <h1>Edit Outcome</h1>

    <form action="{{ route('outcomes.update', $outcome->outcome_ID) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="project_ID" class="form-label">Project</label>
            <select class="form-select" id="project_ID" name="project_ID" required>
                <option value="">Select a project</option>
                @foreach($projects as $project)
                    <option value="{{ $project->project_ID }}" {{ old('project_ID', $outcome->project_ID) == $project->project_ID ? 'selected' : '' }}>{{ $project->title }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $outcome->title) }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" required>{{ old('description', $outcome->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="artifact_link" class="form-label">Artifact Link</label>
            <input type="url" class="form-control" id="artifact_link" name="artifact_link" value="{{ old('artifact_link', $outcome->artifact_link) }}">
        </div>

        <div class="mb-3">
            <label for="outcome_type" class="form-label">Outcome Type</label>
            <input type="text" class="form-control" id="outcome_type" name="outcome_type" value="{{ old('outcome_type', $outcome->outcome_type) }}" required>
        </div>

        <div class="mb-3">
            <label for="quality_certification" class="form-label">Quality Certification</label>
            <input type="text" class="form-control" id="quality_certification" name="quality_certification" value="{{ old('quality_certification', $outcome->quality_certification) }}">
        </div>

        <div class="mb-3">
            <label for="commercialization_status" class="form-label">Commercialization Status</label>
            <input type="text" class="form-control" id="commercialization_status" name="commercialization_status" value="{{ old('commercialization_status', $outcome->commercialization_status) }}">
        </div>

        <div class="mb-3">
            <label for="impact" class="form-label">Impact</label>
            <textarea class="form-control" id="impact" name="impact">{{ old('impact', $outcome->impact) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="date_achieved" class="form-label">Date Achieved</label>
            <input type="date" class="form-control" id="date_achieved" name="date_achieved" value="{{ old('date_achieved', $outcome->date_achieved) }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Outcome</button>
        <a href="{{ route('outcomes.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection