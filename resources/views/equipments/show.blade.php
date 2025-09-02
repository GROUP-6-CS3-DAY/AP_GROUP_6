@extends('layouts.app')

@section('title', 'Equipment Details')

@section('content')
    <h1>Equipment Details</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $equipment->name }}</h5>
            <p class="card-text"><strong>Facility:</strong> {{ optional($equipment->facility)->name ?? $equipment->facility_ID }}</p>
            <p class="card-text"><strong>Capabilities:</strong> {{ $equipment->capabilities }}</p>
            <p class="card-text"><strong>Description:</strong> {{ $equipment->description }}</p>
            <p class="card-text"><strong>Inventory Code:</strong> {{ $equipment->inventory_code }}</p>
            <p class="card-text"><strong>Usage Domain:</strong> {{ $equipment->usage_domain }}</p>
            <p class="card-text"><strong>Support Phase:</strong> {{ $equipment->support_phase }}</p>
        </div>
    </div>

    <a href="{{ route('equipments.edit', $equipment->equipment_ID) }}" class="btn btn-warning mt-3">Edit</a>
    <a href="{{ route('equipments.index') }}" class="btn btn-secondary mt-3">Back to List</a>
@endsection