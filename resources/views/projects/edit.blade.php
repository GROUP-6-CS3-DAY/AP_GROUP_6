@extends('layouts.app')

@section('title', 'Edit Project')

@section('content')
    <h1>Edit Project</h1>

    <form action="{{ route('projects.update', $project->project_ID) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="program_ID" class="form-label">Program ID</label>
            <input type="number" class="form-control" id="program_ID" name="program_ID" value="{{ old('program_ID', $project->program_ID) }}" required>
        </div>

        <div class="mb-3">
            <label for="facility_ID" class="form-label">Facility ID</label>
            <input type="number" class="form-control" id="facility_ID" name="facility_ID" value="{{ old('facility_ID', $project->facility_ID) }}" required>
        </div>

        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $project->title) }}" required>
        </div>

        <div class="mb-3">
            <label for="nature_of_project" class="form-label">Nature of Project</label>
            <textarea class="form-control" id="nature_of_project" name="nature_of_project" required>{{ old('nature_of_project', $project->nature_of_project) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" required>{{ old('description', $project->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="innovation_focus" class="form-label">Innovation Focus</label>
            <textarea class="form-control" id="innovation_focus" name="innovation_focus" required>{{ old('innovation_focus', $project->innovation_focus) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="prototype_stage" class="form-label">Prototype Stage</label>
            <input type="text" class="form-control" id="prototype_stage" name="prototype_stage" value="{{ old('prototype_stage', $project->prototype_stage) }}" required>
        </div>

        <div class="mb-3">
            <label for="testing_requirements" class="form-label">Testing Requirements</label>
            <textarea class="form-control" id="testing_requirements" name="testing_requirements" required>{{ old('testing_requirements', $project->testing_requirements) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="commercialization_plan" class="form-label">Commercialization Plan</label>
            <textarea class="form-control" id="commercialization_plan" name="commercialization_plan" required>{{ old('commercialization_plan', $project->commercialization_plan) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Update Project</button>
        <a href="{{ route('projects.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection