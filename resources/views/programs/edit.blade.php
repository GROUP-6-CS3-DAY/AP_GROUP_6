@extends('layouts.app')

@section('title', 'Edit Program - InnoTrack')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-edit me-2"></i>Edit Program
            </h1>
            <a href="{{ route('programs.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Programs
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('programs.update', $program) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $program->name) }}" required>
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required>{{ old('description', $program->description) }}</textarea>
                @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="national_alignment" class="form-label">National Alignment</label>
                <input type="text" class="form-control @error('national_alignment') is-invalid @enderror" id="national_alignment" name="national_alignment" value="{{ old('national_alignment', $program->national_alignment) }}" required>
                @error('national_alignment')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label for="focus_areas" class="form-label">Focus Areas</label>
                    <select class="form-select @error('focus_areas') is-invalid @enderror" id="focus_areas" name="focus_areas" required>
                        <option value="">Select focus area</option>
                        @foreach($focusAreas as $key => $value)
                        <option value="{{ $key }}" {{ old('focus_areas', $program->focus_areas) == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                    @error('focus_areas')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="phases" class="form-label">Phases</label>
                    <select class="form-select @error('phases') is-invalid @enderror" id="phases" name="phases" required>
                        <option value="">Select phase</option>
                        @foreach($phases as $key => $value)
                        <option value="{{ $key }}" {{ old('phases', $program->phases) == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                    @error('phases')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-1"></i>Save Changes
                </button>
                <a href="{{ route('programs.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
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