@extends('layouts.app')

@section('title', 'Create Program - InnoTrack')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-plus-circle me-2"></i>Create Program
            </h1>
            <a href="{{ route('programs.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Programs
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('programs.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="national_alignment" class="form-label">National Alignment</label>
                <input type="text" class="form-control @error('national_alignment') is-invalid @enderror" id="national_alignment" name="national_alignment" value="{{ old('national_alignment') }}">
                @error('national_alignment')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label for="focus_areas" class="form-label">Focus Area</label>
                    <select class="form-select @error('focus_areas') is-invalid @enderror" id="focus_areas" name="focus_areas">
                        <option value="">Select focus area</option>
                        @foreach($focusAreas as $key => $value)
                        <option value="{{ $key }}" {{ old('focus_areas') == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                    @error('focus_areas')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="phases" class="form-label">Phase</label>
                    <select class="form-select @error('phases') is-invalid @enderror" id="phases" name="phases">
                        <option value="">Select phase</option>
                        @foreach($phases as $key => $value)
                        <option value="{{ $key }}" {{ old('phases') == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                    @error('phases')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date') }}">
                    @error('start_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date') }}">
                    @error('end_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 text-end align-self-end">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i>Create Program
                    </button>
                    <a href="{{ route('programs.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
                </div>
            </div>

        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* small tweaks to match equipment styles */
    .form-label { font-weight: 600; }
</style>
@endpush