@extends('layouts.app')

@section('title', 'Equipments')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0">Equipments</h1>
        <a href="{{ route('equipments.create') }}" class="btn btn-primary">Create New Equipment</a>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Facility</th>
                <th>Inventory Code</th>
                <th>Usage Domain</th>
                <th>Support Phase</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($equipments as $equipment)
                <tr>
                    <td>{{ $equipment->equipment_ID }}</td>
                    <td>{{ $equipment->name }}</td>
                    <td>{{ optional($equipment->facility)->name ?? $equipment->facility_ID }}</td>
                    <td>{{ $equipment->inventory_code }}</td>
                    <td>{{ $equipment->usage_domain }}</td>
                    <td>{{ $equipment->support_phase }}</td>
                    <td>
                        <a href="{{ route('equipments.show', $equipment->equipment_ID) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('equipments.edit', $equipment->equipment_ID) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('equipments.destroy', $equipment->equipment_ID) }}" method="POST" style="display:inline;">
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