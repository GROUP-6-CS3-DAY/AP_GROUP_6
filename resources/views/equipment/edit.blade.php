@extends('layouts.app')

@section('title', 'Edit Equipment - InnoTrack')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0"><i class="fas fa-edit me-2"></i>Edit Equipment</h1>
            <a href="{{ route('equipment.show', $equipment->getId()) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('equipment.update', $equipment->getId()) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label" for="facility_id">Facility</label>
                    <select id="facility_id" name="facility_id" class="form-select @error('facility_id') is-invalid @enderror" required>
                        <option value="">Select facility</option>
                        @foreach($facilities as $facility)
                        <option value="{{ $facility->getId() }}" 
                            {{ old('facility_id', $equipment->getFacilityId()) == $facility->getId() ? 'selected' : '' }}>
                            {{ $facility->getName() }}
                        </option>
                        @endforeach
                    </select>
                    @error('facility_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="inventory_code" class="form-label">Inventory Code</label>
                    <input type="text" class="form-control @error('inventory_code') is-invalid @enderror" 
                           id="inventory_code" name="inventory_code" 
                           value="{{ old('inventory_code', $equipment->getInventoryCode()) }}" required>
                    @error('inventory_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Equipment Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                       id="name" name="name" value="{{ old('name', $equipment->getName()) }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label for="usage_domain" class="form-label">Usage Domain</label>
                    <select class="form-select @error('usage_domain') is-invalid @enderror" 
                            id="usage_domain" name="usage_domain" required>
                        <option value="">Select usage domain</option>
                        @foreach($usageDomains as $key => $value)
                        <option value="{{ $key }}" 
                            {{ old('usage_domain', $equipment->getUsageDomain()->getValue()) == $key ? 'selected' : '' }}>
                            {{ $value }}
                        </option>
                        @endforeach
                    </select>
                    @error('usage_domain')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="support_phase" class="form-label">Support Phase</label>
                    <select class="form-select @error('support_phase') is-invalid @enderror" 
                            id="support_phase" name="support_phase" required>
                        <option value="">Select support phase</option>
                        @foreach($supportPhases as $key => $value)
                        <option value="{{ $key }}" 
                            {{ old('support_phase', $equipment->getSupportPhase()->getValue()) == $key ? 'selected' : '' }}>
                            {{ $value }}
                        </option>
                        @endforeach
                    </select>
                    @error('support_phase')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="4" required>{{ old('description', $equipment->getDescription()) }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Capabilities</label>
                <div class="row">
                    @foreach($capabilities as $key => $value)
                    <div class="col-md-4 col-sm-6 mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="capabilities[]" 
                                   value="{{ $key }}" id="capability_{{ $key }}" 
                                   {{ in_array($key, old('capabilities', $equipment->getCapabilities())) ? 'checked' : '' }}>
                            <label class="form-check-label" for="capability_{{ $key }}">
                                {{ $value }}
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
                @error('capabilities')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-1"></i>Save Changes
                </button>
                <a href="{{ route('equipment.show', $equipment->getId()) }}" class="btn btn-outline-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
    .form-label { font-weight: 600; }
    .form-check-input:checked { background-color: #0d6efd; border-color: #0d6efd; }
</style>
@endpush