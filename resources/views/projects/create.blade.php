@extends('layouts.app')

@section('title', 'Create Project - InnoTrack')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-plus-circle me-2"></i>Create Project
            </h1>
            <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Projects
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('projects.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label for="program_id" class="form-label">Program</label>
                    <select class="form-select @error('program_id') is-invalid @enderror" id="program_id" name="program_id" required>
                        <option value="">Select program</option>
                        @foreach($programs as $program)
                        <option value="{{ $program->program_id }}" {{ old('program_id') == $program->program_id ? 'selected' : '' }}>{{ $program->name }}</option>
                        @endforeach
                    </select>
                    @error('program_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="facility_id" class="form-label">Facility</label>
                    <select class="form-select @error('facility_id') is-invalid @enderror" id="facility_id" name="facility_id" required>
                        <option value="">Select facility</option>
                        @foreach($facilities as $facility)
                        <option value="{{ $facility->facility_id }}" {{ old('facility_id') == $facility->facility_id ? 'selected' : '' }}>{{ $facility->name }}</option>
                        @endforeach
                    </select>
                    @error('facility_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="nature_of_project" class="form-label">Nature of Project</label>
                <textarea class="form-control @error('nature_of_project') is-invalid @enderror" id="nature_of_project" name="nature_of_project" rows="3" required>{{ old('nature_of_project') }}</textarea>
                @error('nature_of_project')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label for="innovation_focus" class="form-label">Innovation Focus</label>
                    <select class="form-select @error('innovation_focus') is-invalid @enderror" id="innovation_focus" name="innovation_focus" required>
                        <option value="">Select innovation focus</option>
                        @foreach($innovationFocus as $key => $value)
                        <option value="{{ $key }}" {{ old('innovation_focus') == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                    @error('innovation_focus')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="prototype_stage" class="form-label">Prototype Stage</label>
                    <select class="form-select @error('prototype_stage') is-invalid @enderror" id="prototype_stage" name="prototype_stage" required>
                        <option value="">Select prototype stage</option>
                        @foreach($prototypeStages as $key => $value)
                        <option value="{{ $key }}" {{ old('prototype_stage') == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                    @error('prototype_stage')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="testing_requirements" class="form-label">Testing Requirements</label>
                <textarea class="form-control @error('testing_requirements') is-invalid @enderror" id="testing_requirements" name="testing_requirements" rows="3" required>{{ old('testing_requirements') }}</textarea>
                @error('testing_requirements')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="commercialization_plan" class="form-label">Commercialization Plan</label>
                <textarea class="form-control @error('commercialization_plan') is-invalid @enderror" id="commercialization_plan" name="commercialization_plan" rows="3" required>{{ old('commercialization_plan') }}</textarea>
                @error('commercialization_plan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-1"></i>Create Project
                </button>
                <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
            </div>

        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
    .form-label { font-weight: 600; }
</style>
@endpush