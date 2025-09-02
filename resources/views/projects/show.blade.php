@extends('layouts.app')

@section('title', 'Project Details')

@section('content')
    <h1>Project Details</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $project->title }}</h5>
            <p class="card-text"><strong>Program ID:</strong> {{ $project->program_ID }}</p>
            <p class="card-text"><strong>Facility ID:</strong> {{ $project->facility_ID }}</p>
            <p class="card-text"><strong>Nature of Project:</strong> {{ $project->nature_of_project }}</p>
            <p class="card-text"><strong>Description:</strong> {{ $project->description }}</p>
            <p class="card-text"><strong>Innovation Focus:</strong> {{ $project->innovation_focus }}</p>
            <p class="card-text"><strong>Prototype Stage:</strong> {{ $project->prototype_stage }}</p>
            <p class="card-text"><strong>Testing Requirements:</strong> {{ $project->testing_requirements }}</p>
            <p class="card-text"><strong>Commercialization Plan:</strong> {{ $project->commercialization_plan }}</p>
        </div>
    </div>

    <a href="{{ route('projects.edit', $project->project_ID) }}" class="btn btn-warning mt-3">Edit</a>
    <a href="{{ route('projects.index') }}" class="btn btn-secondary mt-3">Back to List</a>
@endsection