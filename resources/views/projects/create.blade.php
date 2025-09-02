@extends('layouts.app')

@section('title', 'Create Project')

@section('content')
    <h1>Create New Project</h1>

    <form action="{{ route('projects.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="program_ID" class="form-label">Program</label>
            <select class="form-select" id="program_ID" name="program_ID" required>
                <option value="">Select a program</option>
                @foreach($programs as $program)
                    <option value="{{ $program->program_ID }}" {{ old('program_ID') == $program->program_ID ? 'selected' : '' }}>{{ $program->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="facility_ID" class="form-label">Facility</label>
            <select class="form-select" id="facility_ID" name="facility_ID" required>
                <option value="">Select a facility</option>
                @foreach($facilities as $facility)
                    <option value="{{ $facility->facility_ID }}" {{ old('facility_ID') == $facility->facility_ID ? 'selected' : '' }}>{{ $facility->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
        </div>

        <div class="mb-3">
            <label for="nature_of_project" class="form-label">Nature of Project</label>
            <textarea class="form-control" id="nature_of_project" name="nature_of_project" required>{{ old('nature_of_project') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" required>{{ old('description') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="innovation_focus" class="form-label">Innovation Focus</label>
            <textarea class="form-control" id="innovation_focus" name="innovation_focus" required>{{ old('innovation_focus') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="prototype_stage" class="form-label">Prototype Stage</label>
            <input type="text" class="form-control" id="prototype_stage" name="prototype_stage" value="{{ old('prototype_stage') }}" required>
        </div>

        <div class="mb-3">
            <label for="testing_requirements" class="form-label">Testing Requirements</label>
            <textarea class="form-control" id="testing_requirements" name="testing_requirements" required>{{ old('testing_requirements') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="commercialization_plan" class="form-label">Commercialization Plan</label>
            <textarea class="form-control" id="commercialization_plan" name="commercialization_plan" required>{{ old('commercialization_plan') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Create Project</button>
        <a href="{{ route('projects.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection