@extends('layouts.app')

@section('title', 'Programs')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0">Programs</h1>
        <a href="{{ route('programs.create') }}" class="btn btn-primary">Create New Program</a>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($programs as $program)
                <tr>
                    <td>{{ $program->program_ID }}</td>
                    <td>{{ $program->name }}</td>
                    <td>{{ $program->description }}</td>
                    <td>
                        <a href="{{ route('programs.show', $program) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('programs.edit', $program) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('programs.destroy', $program) }}" method="POST" style="display:inline;">
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