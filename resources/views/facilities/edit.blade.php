@extends('layouts.app')

@section('title', 'Edit ' . $facility->getName() . ' - InnoTrack')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-edit me-2"></i>Edit Facility: {{ $facility->getName() }}
            </h1>
            <div>
                <a href="{{ route('facilities.show', $facility->getId()) }}" class="btn btn-outline-primary me-2">
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
                <form action="{{ route('facilities.update', $facility->getId()) }}" method="POST" id="facilityForm">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Facility Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                id="name" name="name" value="{{ old('name', $facility->getName()) }}" required>
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
                                    {{ old('facility_type', $facility->getFacilityType()->getValue()) == $key ? 'selected' : '' }}>
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
                            <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror"
                                id="location" name="location" value="{{ old('location', $facility->getLocation()) }}" required>
                            @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="capacity" class="form-label">Capacity</label>
                            <input type="number" class="form-control @error('capacity') is-invalid @enderror"
                                id="capacity" name="capacity" value="{{ old('capacity', $facility->getCapacity()) }}" min="0">
                            @error('capacity')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                            id="description" name="description" rows="4" required>{{ old('description', $facility->getDescription()) }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Capabilities</label>
                            <div class="row">
                                @foreach($capabilities as $key => $value)
                                <div class="col-md-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input @error('capabilities') is-invalid @enderror"
                                            type="checkbox" name="capabilities[]"
                                            value="{{ $key }}" id="capability_{{ $key }}"
                                            {{ in_array($key, old('capabilities', $facility->getCapabilities())) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="capability_{{ $key }}">
                                            {{ $value }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @error('capabilities')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="availability_status" class="form-label">Availability Status</label>
                            <select class="form-select @error('availability_status') is-invalid @enderror"
                                id="availability_status" name="availability_status">
                                <option value="available" {{ old('availability_status', $facility->getAvailabilityStatus()) == 'available' ? 'selected' : '' }}>Available</option>
                                <option value="maintenance" {{ old('availability_status', $facility->getAvailabilityStatus()) == 'maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                                <option value="unavailable" {{ old('availability_status', $facility->getAvailabilityStatus()) == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                            </select>
                            @error('availability_status')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Equipment List -->
                    <div class="mb-3">
                        <label class="form-label">Equipment List</label>
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" id="equipmentInput" placeholder="Add equipment...">
                            <button type="button" class="btn btn-outline-secondary" id="addEquipment">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <div id="equipmentList" class="mb-2">
                            <!-- Dynamic equipment will be added here -->
                        </div>
                        <input type="hidden" name="equipment_list" id="equipmentListInput" value="{{ json_encode(old('equipment_list', $facility->getEquipmentList())) }}">
                        @error('equipment_list')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('facilities.show', $facility->getId()) }}" class="btn btn-outline-secondary">
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
                    <li><strong>Name:</strong> {{ $facility->getName() }}</li>
                    <li><strong>Type:</strong> {{ $facility->getFacilityType()->getDisplayName() }}</li>
                    <li><strong>Location:</strong> {{ $facility->getLocation() }}</li>
                    <li><strong>Capacity:</strong> {{ $facility->getCapacity() > 0 ? $facility->getCapacity() : 'Not specified' }}</li>
                    <li><strong>Status:</strong> {{ ucfirst($facility->getAvailabilityStatus()) }}</li>
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
                    <li><strong>Laboratory:</strong> Research and testing laboratories</li>
                    <li><strong>Workshop:</strong> Manufacturing and assembly spaces</li>
                    <li><strong>Office:</strong> Administrative facilities</li>
                    <li><strong>Manufacturing:</strong> Production facilities</li>
                    <li><strong>Storage:</strong> Equipment storage</li>
                    <li><strong>Testing:</strong> Quality assurance facilities</li>
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
        const equipmentInput = document.getElementById('equipmentInput');
        const addEquipmentBtn = document.getElementById('addEquipment');
        const equipmentList = document.getElementById('equipmentList');
        const equipmentListInput = document.getElementById('equipmentListInput');

        let equipment = [];

        // Load existing equipment
        try {
            const existingEquipment = JSON.parse(equipmentListInput.value);
            if (Array.isArray(existingEquipment)) {
                equipment = existingEquipment;
                updateEquipmentDisplay();
            }
        } catch (e) {
            equipment = [];
        }

        // Add equipment
        addEquipmentBtn.addEventListener('click', function() {
            addEquipment();
        });

        // Add equipment on Enter key
        equipmentInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addEquipment();
            }
        });

        function addEquipment() {
            const equipmentItem = equipmentInput.value.trim();
            if (equipmentItem && !equipment.includes(equipmentItem)) {
                equipment.push(equipmentItem);
                equipmentInput.value = '';
                updateEquipmentDisplay();
                updateEquipmentInput();
            }
        }

        function removeEquipment(index) {
            equipment.splice(index, 1);
            updateEquipmentDisplay();
            updateEquipmentInput();
        }

        function updateEquipmentDisplay() {
            equipmentList.innerHTML = '';
            equipment.forEach((equipmentItem, index) => {
                const badge = document.createElement('span');
                badge.className = 'badge bg-primary me-2 mb-2';
                badge.innerHTML = `
                    ${equipmentItem}
                    <i class="fas fa-times ms-1" style="cursor: pointer;" onclick="removeEquipment(${index})"></i>
                `;
                equipmentList.appendChild(badge);
            });
        }

        function updateEquipmentInput() {
            equipmentListInput.value = JSON.stringify(equipment);
        }

        // Form validation
        form.addEventListener('submit', function(e) {
            const name = document.getElementById('name').value;
            const facilityType = document.getElementById('facility_type').value;
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

            if (equipment.length === 0) {
                e.preventDefault();
                alert('Please add at least one equipment item.');
                return false;
            }
        });

        // Make removeEquipment function global
        window.removeEquipment = removeEquipment;
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