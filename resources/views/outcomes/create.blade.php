@extends('layouts.app')

@section('title', 'Create Outcome')

@section('content')
    <h1>Create New Outcome</h1>

    <form action="{{ route('outcomes.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="project_ID" class="form-label">Project</label>
            <select class="form-select" id="project_ID" name="project_ID" required>
                <option value="">Select a project</option>
                @foreach($projects as $project)
                    <option value="{{ $project->project_ID }}" {{ old('project_ID') == $project->project_ID ? 'selected' : '' }}>{{ $project->title }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" required>{{ old('description') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="artifact_link" class="form-label">Artifact Link</label>
            <input type="url" class="form-control" id="artifact_link" name="artifact_link" value="{{ old('artifact_link') }}">
        </div>

        <div class="mb-3">
            <label for="outcome_type" class="form-label">Outcome Type</label>
            <input type="text" class="form-control" id="outcome_type" name="outcome_type" value="{{ old('outcome_type') }}" required>
        </div>

        <div class="mb-3">
            <label for="quality_certification" class="form-label">Quality Certification</label>
            <input type="text" class="form-control" id="quality_certification" name="quality_certification" value="{{ old('quality_certification') }}">
        </div>

        <div class="mb-3">
            <label for="commercialization_status" class="form-label">Commercialization Status</label>
            <input type="text" class="form-control" id="commercialization_status" name="commercialization_status" value="{{ old('commercialization_status') }}">
        </div>

        <div class="mb-3">
            <label for="impact" class="form-label">Impact</label>
            <textarea class="form-control" id="impact" name="impact">{{ old('impact') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="date_achieved" class="form-label">Date Achieved</label>
            <input type="date" class="form-control" id="date_achieved" name="date_achieved" value="{{ old('date_achieved') }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Create Outcome</button>
        <a href="{{ route('outcomes.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection