@extends('layouts.app')

@section('title', 'Program Details')

@section('content')
    <h1>Program Details</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $program->name }}</h5>
            <p class="card-text"><strong>Description:</strong> {{ $program->description }}</p>
            <p class="card-text"><strong>National Alignment:</strong> {{ $program->national_alignment }}</p>
            <p class="card-text"><strong>Focus Areas:</strong> {{ $program->focus_areas }}</p>
            <p class="card-text"><strong>Phases:</strong> {{ $program->phases }}</p>
        </div>
    </div>

    <a href="{{ route('programs.edit', $program) }}" class="btn btn-warning mt-3">Edit</a>
    <a href="{{ route('programs.index') }}" class="btn btn-secondary mt-3">Back to List</a>
@endsection