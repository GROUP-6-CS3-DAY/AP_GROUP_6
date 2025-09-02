@extends('layouts.app')

@section('title', 'Outcome Details')

@section('content')
    <h1>Outcome Details</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $outcome->title }}</h5>
            <p class="card-text"><strong>Project:</strong> {{ optional($outcome->project)->title ?? $outcome->project_ID }}</p>
            <p class="card-text"><strong>Type:</strong> {{ $outcome->outcome_type }}</p>
            <p class="card-text"><strong>Description:</strong> {{ $outcome->description }}</p>
            <p class="card-text"><strong>Artifact Link:</strong> @if($outcome->artifact_link)&lt;a href="{{ $outcome->artifact_link }}" target="_blank"&gt;View&lt;/a&gt;@endif</p>
            <p class="card-text"><strong>Quality Certification:</strong> {{ $outcome->quality_certification }}</p>
            <p class="card-text"><strong>Commercialization Status:</strong> {{ $outcome->commercialization_status }}</p>
            <p class="card-text"><strong>Impact:</strong> {{ $outcome->impact }}</p>
            <p class="card-text"><strong>Date Achieved:</strong> {{ $outcome->date_achieved }}</p>
        </div>
    </div>

    <a href="{{ route('outcomes.edit', $outcome->outcome_ID) }}" class="btn btn-warning mt-3">Edit</a>
    <a href="{{ route('outcomes.index') }}" class="btn btn-secondary mt-3">Back to List</a>
@endsection