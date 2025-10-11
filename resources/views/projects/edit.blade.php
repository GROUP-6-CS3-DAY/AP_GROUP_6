@extends('layouts.app')

@section('title', 'Edit Project - InnoTrack')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0"><i class="fas fa-edit me-2"></i>Edit Project</h1>
            <a href="{{ route('projects.show', $project->getId()) }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Back</a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('projects.update', $project->getId()) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label" for="program_id">Program</label>
                    <select id="program_id" name="program_id" class="form-select @error('program_id') is-invalid @enderror" required>
                        <option value="">Select program</option>
                        @foreach($programs as $program)
                        <option value="{{ $program['id'] }}" {{ old('program_id', $project->getProgramId()) == $program['id'] ? 'selected' : '' }}>
                            {{ $program['title'] }}
                        </option>
                        @endforeach
                    </select>
                    @error('program_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="facility_id">Facility</label>
                    <select id="facility_id" name="facility_id" class="form-select @error('facility_id') is-invalid @enderror" required>
                        <option value="">Select facility</option>
                        @foreach($facilities as $facility)
                        <option value="{{ $facility['id'] }}" {{ old('facility_id', $project->getFacilityId()) == $facility['id'] ? 'selected' : '' }}>
                            {{ $facility['name'] }}
                        </option>
                        @endforeach
                    </select>
                    @error('facility_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label" for="title">Title</label>
                <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $project->getTitle()) }}" required>
                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label" for="innovation_focus">Innovation Focus</label>
                    <select id="innovation_focus" name="innovation_focus" class="form-select @error('innovation_focus') is-invalid @enderror" required>
                        <option value="">Select innovation focus</option>
                        @foreach($innovationFocus as $key => $label)
                        <option value="{{ $key }}" {{ old('innovation_focus', $project->getInnovationFocus()->getValue()) == $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                    @error('innovation_focus')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="prototype_stage">Prototype Stage</label>
                    <select id="prototype_stage" name="prototype_stage" class="form-select @error('prototype_stage') is-invalid @enderror" required>
                        <option value="">Select prototype stage</option>
                        @foreach($prototypeStages as $key => $label)
                        <option value="{{ $key }}" {{ old('prototype_stage', $project->getPrototypeStage()->getValue()) == $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                    @error('prototype_stage')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label" for="nature_of_project">Nature of Project</label>
                <textarea id="nature_of_project" name="nature_of_project" rows="3" class="form-control @error('nature_of_project') is-invalid @enderror" required>{{ old('nature_of_project', $project->getNatureOfProject()) }}</textarea>
                @error('nature_of_project')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="description">Description</label>
                <textarea id="description" name="description" rows="4" class="form-control @error('description') is-invalid @enderror" required>{{ old('description', $project->getDescription()) }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="testing_requirements">Testing Requirements</label>
                <textarea id="testing_requirements" name="testing_requirements" rows="3" class="form-control @error('testing_requirements') is-invalid @enderror" required>{{ old('testing_requirements', $project->getTestingRequirements()) }}</textarea>
                @error('testing_requirements')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="commercialization_plan">Commercialization Plan</label>
                <textarea id="commercialization_plan" name="commercialization_plan" rows="4" class="form-control @error('commercialization_plan') is-invalid @enderror" required>{{ old('commercialization_plan', $project->getCommercializationPlan()) }}</textarea>
                @error('commercialization_plan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i>Save Changes</button>
                <a href="{{ route('projects.show', $project->getId()) }}" class="btn btn-outline-secondary ms-2">Cancel</a>
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