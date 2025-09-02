@extends('layouts.app')

@section('title', 'Outcomes')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0">Outcomes</h1>
        <a href="{{ route('outcomes.create') }}" class="btn btn-primary">Create New Outcome</a>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Project</th>
                <th>Type</th>
                <th>Date Achieved</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($outcomes as $outcome)
                <tr>
                    <td>{{ $outcome->outcome_ID }}</td>
                    <td>{{ $outcome->title }}</td>
                    <td>{{ optional($outcome->project)->title ?? $outcome->project_ID ?? 'N/A' }}</td>
                    <td>{{ $outcome->outcome_type }}</td>
                    <td>{{ $outcome->date_achieved }}</td>
                    <td>
                        <a href="{{ route('outcomes.show', $outcome->outcome_ID) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('outcomes.edit', $outcome->outcome_ID) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('outcomes.destroy', $outcome->outcome_ID) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection