@extends('layouts.app')

@section('title', 'Edit ' . $facility->name . ' - InnoTrack')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-edit me-2"></i>Edit Facility: {{ $facility->name }}
            </h1>
            <div>
                <a href="{{ route('facilities.show', $facility) }}" class="btn btn-outline-primary me-2">
                    <i class="fas fa-eye me-1"></i>View Facility
                </a>
                <a href="{{ route('facilities.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back to Facilities
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-building me-2"></i>Edit Facility Information
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('facilities.update', $facility) }}" method="POST" id="facilityForm">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Facility Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                id="name" name="name" value="{{ old('name', $facility->name) }}" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="facility_type" class="form-label">Facility Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('facility_type') is-invalid @enderror"
                                id="facility_type" name="facility_type" required>
                                <option value="">Select Facility Type</option>
                                @foreach($facilityTypes as $key => $value)
                                <option value="{{ $key }}"
                                    {{ old('facility_type', $facility->facility_type) == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                                @endforeach
                            </select>
                            @error('facility_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="partner_organization" class="form-label">Partner Organization <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('partner_organization') is-invalid @enderror"
                                id="partner_organization" name="partner_organization"
                                value="{{ old('partner_organization', $facility->partner_organization) }}" required>
                            @error('partner_organization')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                            <input type="hidden" name="capabilities" id="capabilitiesInput" value="{{ old('capabilities', json_encode($facility->capabilities ?? [])) }}">
                            @error('capabilities')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('location') is-invalid @enderror"
                            id="location" name="location" value="{{ old('location', $facility->location) }}" required>
                        @error('location')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Provide the physical address or location description of the facility.</div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                            id="description" name="description" rows="4" required>{{ old('description', $facility->description) }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Provide a detailed description of the facility's purpose, features, and capabilities.</div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('facilities.show', $facility) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save me-1"></i>Update Facility
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Current Facility Info -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Current Information
                </h6>
            </div>
            <div class="card-body">
                <h6>Facility Details</h6>
                <ul class="list-unstyled small text-muted">
                    <li><strong>Name:</strong> {{ $facility->name }}</li>
                    <li><strong>Type:</strong> {{ $facility->getFacilityTypeOptions()[$facility->facility_type] ?? $facility->facility_type }}</li>
                    <li><strong>Partner:</strong> {{ $facility->partner_organization }}</li>
                    <li><strong>Location:</strong> {{ $facility->location }}</li>
                    <li><strong>Created:</strong> {{ $facility->created_at->format('M d, Y') }}</li>
                    <li><strong>Updated:</strong> {{ $facility->updated_at->format('M d, Y') }}</li>
                </ul>
            </div>
        </div>

        <!-- Help Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-question-circle me-2"></i>Help
                </h6>
            </div>
            <div class="card-body">
                <h6>Facility Types</h6>
                <ul class="list-unstyled small text-muted">
                    <li><strong>Lab:</strong> Research and testing laboratories</li>
                    <li><strong>Workshop:</strong> Manufacturing and assembly spaces</li>
                    <li><strong>Testing Center:</strong> Quality assurance facilities</li>
                    <li><strong>Maker Space:</strong> Creative prototyping areas</li>
                    <li><strong>Training Center:</strong> Educational facilities</li>
                    <li><strong>Innovation Hub:</strong> Collaborative workspaces</li>
                </ul>

                <h6 class="mt-3">Capability Examples</h6>
                <ul class="list-unstyled small text-muted">
                    <li><strong>CNC:</strong> Computer numerical control machining</li>
                    <li><strong>PCB Fabrication:</strong> Circuit board production</li>
                    <li><strong>3D Printing:</strong> Additive manufacturing</li>
                    <li><strong>Materials Testing:</strong> Quality validation</li>
                    <li><strong>Prototyping:</strong> Rapid development</li>
                    <li><strong>Training:</strong> Skill development programs</li>
                </ul>
            </div>
        </div>

        <!-- Warning -->
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Note:</strong> Changing facility details may affect associated services and equipment. Make sure all changes are accurate.
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
        const form = document.getElementById('facilityForm');

        let capabilities = [];

        // Load existing capabilities
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
            const name = document.getElementById('name').value;
            const facilityType = document.getElementById('facility_type').value;
            const partnerOrg = document.getElementById('partner_organization').value;
            const location = document.getElementById('location').value;
            const description = document.getElementById('description').value;

            if (!name.trim()) {
                e.preventDefault();
                alert('Please enter a facility name.');
                return false;
            }

            if (!facilityType) {
                e.preventDefault();
                alert('Please select a facility type.');
                return false;
            }

            if (!partnerOrg.trim()) {
                e.preventDefault();
                alert('Please enter a partner organization.');
                return false;
            }

            if (!location.trim()) {
                e.preventDefault();
                alert('Please enter a location.');
                return false;
            }

            if (!description.trim()) {
                e.preventDefault();
                alert('Please enter a description.');
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