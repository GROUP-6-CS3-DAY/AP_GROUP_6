@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Participants</h2>
    <a href="{{ route('participants.create') }}" class="btn btn-primary mb-3">Add Participant</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Affiliation</th>
                <th>Specialization</th>
                <th>Institution</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($participants as $participant)
                <tr>
                    <td>{{ $participant->full_name }}</td>
                    <td>{{ $participant->email }}</td>
                    <td>{{ ucfirst($participant->affiliation) }}</td>
                    <td>{{ ucfirst($participant->specialization) }}</td>
                    <td>{{ strtoupper($participant->institution) }}</td>
                    <td>
                        <a href="{{ route('participants.show', $participant->participant_id) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('participants.edit', $participant->participant_id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('participants.destroy', $participant->participant_id) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this participant?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
