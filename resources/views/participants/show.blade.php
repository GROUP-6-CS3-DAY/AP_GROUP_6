@extends('layouts.app')

@section('content')
<div class="container">
    <h2>{{ $participant->full_name }}</h2>
    <ul>
        <li>Email: {{ $participant->email }}</li>
        <li>Affiliation: {{ ucfirst($participant->affiliation) }}</li>
        <li>Specialization: {{ ucfirst($participant->specialization) }}</li>
        <li>Cross Skill Trained: {{ $participant->cross_skill_trained ? 'Yes' : 'No' }}</li>
        <li>Institution: {{ strtoupper($participant->institution) }}</li>
    </ul>
    <a href="{{ route('participants.index') }}" class="btn btn-secondary">Back</a>
</div>
@endsection
