@extends('layouts.app')

@section('title', 'Equipment Details - InnoTrack')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-tools me-2"></i>Equipment Details
            </h1>
            <div>
                <a href="{{ route('equipment.edit', $equipment) }}" class="btn btn-outline-warning me-2">
                    <i class="fas fa-edit me-1"></i>Edit
                </a>
                <form action="{{ route('equipment.destroy', $equipment) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this equipment?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger" type="submit">
                        <i class="fas fa-trash me-1"></i>Delete
                    </button>
                </form>
                <a href="{{ route('equipment.index') }}" class="btn btn-outline-secondary ms-2">
                    <i class="fas fa-arrow-left me-1"></i>Back
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <h4 class="mb-1">{{ $equipment->name }}</h4>
                <p class="text-muted mb-3">{{ $equipment->description }}</p>

                <div class="mb-3">
                    <strong>Facility: </strong>
                    @if($equipment->facility)
                    <a href="{{ route('facilities.show', $equipment->facility) }}" class="text-decoration-none">
                        <i class="fas fa-building me-1"></i>{{ $equipment->facility->name }}
                    </a>
                    @else
                    <span class="text-muted">—</span>
                    @endif
                </div>

                <div class="mb-3">
                    <strong>Inventory Code: </strong>
                    <code>{{ $equipment->inventory_code }}</code>
                </div>

                <div class="mb-3">
                    <strong>Usage Domain: </strong>
                    <span class="badge bg-info">{{ $usageDomains[$equipment->usage_domain] ?? $equipment->usage_domain }}</span>
                </div>

                <div class="mb-3">
                    <strong>Support Phase: </strong>
                    <span class="badge bg-warning">{{ $supportPhases[$equipment->support_phase] ?? $equipment->support_phase }}</span>
                </div>

                <div class="mb-3">
                    <strong>Capabilities: </strong>
                    @if($equipment->capabilities && count($equipment->capabilities) > 0)
                    <div class="d-flex flex-wrap gap-2 mt-2">
                        @foreach($equipment->capabilities as $capability)
                        <span class="badge bg-primary">{{ $capabilities[$capability] ?? $capability }}</span>
                        @endforeach
                    </div>
                    @else
                    <span class="text-muted">No capabilities listed</span>
                    @endif
                </div>
            </div>

            <div class="col-md-4">
                <div class="border rounded p-3 bg-light">
                    <p class="mb-1"><small class="text-muted">Created</small></p>
                    <p class="mb-2">{{ $equipment->created_at->diffForHumans() }}<br><small class="text-muted">{{ $equipment->created_at->format('Y-m-d H:i') }}</small></p>

                    <p class="mb-1"><small class="text-muted">Last Updated</small></p>
                    <p class="mb-2">{{ $equipment->updated_at->diffForHumans() }}<br><small class="text-muted">{{ $equipment->updated_at->format('Y-m-d H:i') }}</small></p>

                    <hr>
                    <div class="text-center">
                        <h4 class="text-success">{{ count($equipment->capabilities ?? []) }}</h4>
                        <small class="text-muted">Total Capabilities</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .badge { font-size: 0.85em; }
    code { font-size: 0.9em; }
</style>
@endpush