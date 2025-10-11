@extends('layouts.app')

@section('title', 'Equipment Details - InnoTrack')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0"><i class="fas fa-tools me-2"></i>Equipment Details</h1>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('equipment.edit', $equipment) }}" class="btn btn-outline-warning"><i class="fas fa-edit me-1"></i>Edit</a>
                <form action="{{ route('equipment.destroy', $equipment) }}" method="POST" onsubmit="return confirm('Delete this equipment?');">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger" type="submit"><i class="fas fa-trash me-1"></i>Delete</button>
                </form>
                <a href="{{ route('equipment.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Back</a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-body">
                <h4 class="mb-2">{{ $equipment->name }}</h4>
                <p class="text-muted mb-4">{{ $equipment->description }}</p>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="p-3 border rounded bg-light h-100">
                            <small class="text-muted d-block mb-1">Facility</small>
                            @if($equipment->facility)
                                <a href="{{ route('facilities.show', $equipment->facility) }}" class="text-decoration-none"><i class="fas fa-building me-1"></i>{{ $equipment->facility->name }}</a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 border rounded bg-light h-100">
                            <small class="text-muted d-block mb-1">Inventory Code</small>
                            <code>{{ $equipment->inventory_code }}</code>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 border rounded bg-light h-100">
                            <small class="text-muted d-block mb-1">Usage Domain</small>
                            <span class="badge bg-info">{{ $usageDomains[$equipment->usage_domain] ?? $equipment->usage_domain }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 border rounded bg-light h-100">
                            <small class="text-muted d-block mb-1">Support Phase</small>
                            <span class="badge bg-warning">{{ $supportPhases[$equipment->support_phase] ?? $equipment->support_phase }}</span>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="text-uppercase text-muted fw-semibold mb-2">Capabilities</h6>
                    @if($equipment->capabilities && count($equipment->capabilities) > 0)
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($equipment->capabilities as $capability)
                                <span class="badge bg-primary">{{ $capabilities[$capability] ?? $capability }}</span>
                            @endforeach
                        </div>
                    @else
                        <span class="text-muted">No capabilities listed</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header py-2 d-flex justify-content-between align-items-center"><span class="fw-semibold"><i class="fas fa-info-circle me-2"></i>Meta</span></div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted d-block">Created</small>
                    <span>{{ $equipment->created_at->diffForHumans() }}</span><br>
                    <small class="text-muted">{{ $equipment->created_at->format('Y-m-d H:i') }}</small>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Last Updated</small>
                    <span>{{ $equipment->updated_at->diffForHumans() }}</span><br>
                    <small class="text-muted">{{ $equipment->updated_at->format('Y-m-d H:i') }}</small>
                </div>
                <hr>
                <div class="text-center">
                    <h4 class="text-success mb-0">{{ count($equipment->capabilities ?? []) }}</h4>
                    <small class="text-muted">Capabilities</small>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h6 class="text-uppercase text-muted fw-semibold mb-3">Quick Actions</h6>
                <div class="d-grid gap-2">
                    <a href="{{ route('equipment.edit', $equipment) }}" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit me-1"></i>Edit Equipment</a>
                    <a href="{{ route('equipment.index') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-list me-1"></i>All Equipment</a>
                    <a href="{{ route('equipment.create') }}" class="btn btn-sm btn-outline-success"><i class="fas fa-plus me-1"></i>New Equipment</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>.badge{font-size:.7rem;letter-spacing:.5px}code{font-size:.8rem}</style>
@endpush