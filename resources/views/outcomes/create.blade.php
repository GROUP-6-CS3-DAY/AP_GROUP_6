@extends('layouts.app')

@section('title', 'Create Outcome - InnoTrack')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0"><i class="fas fa-plus me-2"></i>Create Outcome</h1>
            <a href="{{ route('outcomes.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('outcomes.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label" for="project_id">Project</label>
                <select id="project_id" name="project_id" class="form-select @error('project_id') is-invalid @enderror" required>
                    <option value="">Select project</option>
                    @foreach($projects as $project)
                    <option value="{{ $project->getId() }}" {{ old('project_id') == $project->getId() ? 'selected' : '' }}>
                        {{ $project->getTitle() }}
                    </option>
                    @endforeach
                </select>
                @error('project_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                           id="title" name="title" value="{{ old('title') }}" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="outcome_type" class="form-label">Type</label>
                    <select class="form-select @error('outcome_type') is-invalid @enderror" 
                            id="outcome_type" name="outcome_type" required>
                        <option value="">Select type</option>
                        <option value="publication" {{ old('outcome_type') == 'publication' ? 'selected' : '' }}>Publication</option>
                        <option value="patent" {{ old('outcome_type') == 'patent' ? 'selected' : '' }}>Patent</option>
                        <option value="product" {{ old('outcome_type') == 'product' ? 'selected' : '' }}>Product</option>
                        <option value="prototype" {{ old('outcome_type') == 'prototype' ? 'selected' : '' }}>Prototype</option>
                        <option value="certification" {{ old('outcome_type') == 'certification' ? 'selected' : '' }}>Certification</option>
                        <option value="other" {{ old('outcome_type') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('outcome_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label for="date_achieved" class="form-label">Date Achieved</label>
                    <input type="date" class="form-control @error('date_achieved') is-invalid @enderror" 
                           id="date_achieved" name="date_achieved" value="{{ old('date_achieved') }}" required>
                    @error('date_achieved')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="commercialization_status" class="form-label">Commercialization Status</label>
                    <select class="form-select @error('commercialization_status') is-invalid @enderror" 
                            id="commercialization_status" name="commercialization_status">
                        <option value="">Select status</option>
                        <option value="Ready" {{ old('commercialization_status') == 'Ready' ? 'selected' : '' }}>Ready</option>
                        <option value="In Progress" {{ old('commercialization_status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="Commercialized" {{ old('commercialization_status') == 'Commercialized' ? 'selected' : '' }}>Commercialized</option>
                        <option value="Not Applicable" {{ old('commercialization_status') == 'Not Applicable' ? 'selected' : '' }}>Not Applicable</option>
                    </select>
                    @error('commercialization_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="quality_certification" class="form-label">Quality Certification</label>
                <input type="text" class="form-control @error('quality_certification') is-invalid @enderror" 
                       id="quality_certification" name="quality_certification" value="{{ old('quality_certification') }}">
                @error('quality_certification')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="impact" class="form-label">Impact</label>
                <textarea class="form-control @error('impact') is-invalid @enderror" 
                          id="impact" name="impact" rows="3">{{ old('impact') }}</textarea>
                @error('impact')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="artifact_link" class="form-label">Artifact Link</label>
                <input type="url" class="form-control @error('artifact_link') is-invalid @enderror" 
                       id="artifact_link" name="artifact_link" value="{{ old('artifact_link') }}" 
                       placeholder="https://example.com/artifact">
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