@extends('layouts.app')

@section('title', $equipment->getName() . ' - InnoTrack')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">{{ $equipment->getName() }}</h1>
            <div>
                <a href="{{ route('equipment.edit', $equipment->getId()) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-1"></i>Edit
                </a>
                <a href="{{ route('equipment.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Equipment Details</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Inventory Code:</strong>
                        <span class="badge bg-primary ms-2">{{ $equipment->getInventoryCode() }}</span>
                    </div>
                    <div class="col-md-6">
                        <strong>Usage Domain:</strong>
                        <span class="badge bg-info ms-2">{{ $equipment->getUsageDomain()->getDisplayName() }}</span>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <strong>Support Phase:</strong>
                        <span class="badge bg-secondary ms-2">{{ $equipment->getSupportPhase()->getDisplayName() }}</span>
                    </div>
                </div>
                
                <div class="mb-3">
                    <strong>Description:</strong>
                    <p>{{ $equipment->getDescription() }}</p>
                </div>
                
                <div class="mb-0">
                    <strong>Capabilities:</strong>
                    <div class="mt-2">
                        @foreach($equipment->getCapabilities() as $capability)
                        <span class="badge bg-success me-1 mb-1">{{ $capabilities[$capability] ?? ucwords(str_replace('_', ' ', $capability)) }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Equipment Status</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>Available for Training:</span>
                    <span class="badge bg-{{ $equipment->isAvailableForPhase('training') ? 'success' : 'secondary' }}">
                        {{ $equipment->isAvailableForPhase('training') ? 'Yes' : 'No' }}
                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>Available for Prototyping:</span>
                    <span class="badge bg-{{ $equipment->isAvailableForPhase('prototyping') ? 'success' : 'secondary' }}">
                        {{ $equipment->isAvailableForPhase('prototyping') ? 'Yes' : 'No' }}
                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>Available for Testing:</span>
                    <span class="badge bg-{{ $equipment->isAvailableForPhase('testing') ? 'success' : 'secondary' }}">
                        {{ $equipment->isAvailableForPhase('testing') ? 'Yes' : 'No' }}
                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span>Capabilities Count:</span>
                    <span class="badge bg-info">{{ count($equipment->getCapabilities()) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection