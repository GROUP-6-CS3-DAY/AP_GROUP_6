@extends('layouts.app')

@section('title', 'Edit Program')

@section('content')
    <h1>Edit Program</h1>

    <form action="{{ route('programs.update', $program) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $program->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" required>{{ old('description', $program->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="national_alignment" class="form-label">National Alignment</label>
            <input type="text" class="form-control" id="national_alignment" name="national_alignment" value="{{ old('national_alignment', $program->national_alignment) }}" required>
        </div>

        <div class="mb-3">
            <label for="focus_areas" class="form-label">Focus Areas</label>
            <input type="text" class="form-control" id="focus_areas" name="focus_areas" value="{{ old('focus_areas', $program->focus_areas) }}" required>
        </div>

        <div class="mb-3">
            <label for="phases" class="form-label">Phases</label>
            <input type="text" class="form-control" id="phases" name="phases" value="{{ old('phases', $program->phases) }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Program</button>
        <a href="{{ route('programs.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection