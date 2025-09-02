@extends('layouts.app')

@section('title', 'Create Equipment - InnoTrack')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-plus me-2"></i>Create New Equipment
            </h1>
            <a href="{{ route('equipment.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Equipment
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-tools me-2"></i>Equipment Information
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('equipment.store') }}" method="POST" id="equipmentForm">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Equipment Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="facility_id" class="form-label">Facility <span class="text-danger">*</span></label>
                            <select class="form-select @error('facility_id') is-invalid @enderror"
                                id="facility_id" name="facility_id" required>
                                <option value="">Select Facility</option>
                                @foreach($facilities as $facility)
                                <option value="{{ $facility->id }}" {{ old('facility_id') == $facility->id ? 'selected' : '' }}>
                                    {{ $facility->name }} ({{ $facility->facility_type }})
                                </option>
                                @endforeach
                            </select>
                            @error('facility_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="usage_domain" class="form-label">Usage Domain <span class="text-danger">*</span></label>
                            <select class="form-select @error('usage_domain') is-invalid @enderror"
                                id="usage_domain" name="usage_domain" required>
                                <option value="">Select Usage Domain</option>
                                @foreach($usageDomains as $key => $value)
                                <option value="{{ $key }}" {{ old('usage_domain') == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                                @endforeach
                            </select>
                            @error('usage_domain')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="support_phase" class="form-label">Support Phase <span class="text-danger">*</span></label>
                            <select class="form-select @error('support_phase') is-invalid @enderror"
                                id="support_phase" name="support_phase" required>
                                <option value="">Select Support Phase</option>
                                @foreach($supportPhases as $key => $value)
                                <option value="{{ $key }}" {{ old('support_phase') == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                                @endforeach
                            </select>
                            @error('support_phase')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="inventory_code" class="form-label">Inventory Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('inventory_code') is-invalid @enderror"
                                id="inventory_code" name="inventory_code" value="{{ old('inventory_code') }}" required>
                            @error('inventory_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Unique tracking code for this equipment</div>
                        </div>
                        <div class="col-md-6">
                            <label for="capabilities" class="form-label">Capabilities <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="capabilityInput" placeholder="Add capability...">
                                <button type="button" class="btn btn-outline-secondary" id="addCapability">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div id="capabilitiesList" class="mt-2">
                                <!-- Dynamic capabilities will be added here -->
                            </div>
                            <input type="hidden" name="capabilities" id="capabilitiesInput" value="{{ old('capabilities', '[]') }}">
                            @error('capabilities')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                            id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Provide a detailed description of the equipment, its specifications, and intended use.</div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('equipment.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-1"></i>Create Equipment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Information
                </h6>
            </div>
            <div class="card-body">
                <h6>Usage Domains</h6>
                <ul class="list-unstyled small text-muted">
                    <li><strong>Electronics:</strong> Circuit boards, sensors, microcontrollers</li>
                    <li><strong>Mechanical:</strong> Motors, gears, structural components</li>
                    <li><strong>IoT:</strong> Connected devices, smart systems</li>
                    <li><strong>Renewable Energy:</strong> Solar panels, wind turbines</li>
                    <li><strong>Automation:</strong> Robotics, control systems</li>
                    <li><strong>Testing:</strong> Quality assurance equipment</li>
                    <li><strong>Prototyping:</strong> 3D printers, laser cutters</li>
                </ul>

                <h6 class="mt-3">Support Phases</h6>
                <ul class="list-unstyled small text-muted">
                    <li><strong>Training:</strong> Learning and skill development</li>
                    <li><strong>Prototyping:</strong> Initial development and testing</li>
                    <li><strong>Testing:</strong> Quality validation and verification</li>
                    <li><strong>Commercialization:</strong> Market-ready production</li>
                </ul>

                <div class="alert alert-info mt-3">
                    <i class="fas fa-lightbulb me-2"></i>
                    <strong>Tip:</strong> Inventory codes must be unique across all equipment. Use a consistent naming convention like "EQ-001", "EQ-002", etc.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const capabilityInput = document.getElementById('capabilityInput');
        const addCapabilityBtn = document.getElementById('addCapability');
        const capabilitiesList = document.getElementById('capabilitiesList');
        const capabilitiesInput = document.getElementById('capabilitiesInput');
        const form = document.getElementById('equipmentForm');

        let capabilities = [];

        // Load existing capabilities if any
        try {
            const existingCapabilities = JSON.parse(capabilitiesInput.value);
            if (Array.isArray(existingCapabilities)) {
                capabilities = existingCapabilities;
                updateCapabilitiesDisplay();
            }
        } catch (e) {
            capabilities = [];
        }

        // Add capability
        addCapabilityBtn.addEventListener('click', function() {
            addCapability();
        });

        // Add capability on Enter key
        capabilityInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addCapability();
            }
        });

        function addCapability() {
            const capability = capabilityInput.value.trim();
            if (capability && !capabilities.includes(capability)) {
                capabilities.push(capability);
                capabilityInput.value = '';
                updateCapabilitiesDisplay();
                updateCapabilitiesInput();
            }
        }

        function removeCapability(index) {
            capabilities.splice(index, 1);
            updateCapabilitiesDisplay();
            updateCapabilitiesInput();
        }

        function updateCapabilitiesDisplay() {
            capabilitiesList.innerHTML = '';
            capabilities.forEach((capability, index) => {
                const badge = document.createElement('span');
                badge.className = 'badge bg-primary me-2 mb-2';
                badge.innerHTML = `
                    ${capability}
                    <i class="fas fa-times ms-1" style="cursor: pointer;" onclick="removeCapability(${index})"></i>
                `;
                capabilitiesList.appendChild(badge);
            });
        }

        function updateCapabilitiesInput() {
            capabilitiesInput.value = JSON.stringify(capabilities);
        }

        // Form validation
        form.addEventListener('submit', function(e) {
            const facilityId = document.getElementById('facility_id').value;
            const name = document.getElementById('name').value;
            const inventoryCode = document.getElementById('inventory_code').value;

            if (!facilityId) {
                e.preventDefault();
                alert('Please select a facility for this equipment.');
                return false;
            }

            if (!name.trim()) {
                e.preventDefault();
                alert('Please enter an equipment name.');
                return false;
            }

            if (!inventoryCode.trim()) {
                e.preventDefault();
                alert('Please enter an inventory code.');
                return false;
            }

            if (capabilities.length === 0) {
                e.preventDefault();
                alert('Please add at least one capability.');
                return false;
            }
        });

        // Make removeCapability function global
        window.removeCapability = removeCapability;
    });
</script>
@endpush

@push('styles')
<style>
    .badge {
        font-size: 0.875em;
    }

    .badge i {
        font-size: 0.75em;
    }

    .badge i:hover {
        color: #dc3545 !important;
    }
</style>
@endpush