@extends('layouts.app')

@section('title', 'Create Facility - InnoTrack')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-plus me-2"></i>Create New Facility
            </h1>
            <a href="{{ route('facilities.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Facilities
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-building me-2"></i>Facility Information
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('facilities.store') }}" method="POST">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Facility Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                id="name" name="name" value="{{ old('name') }}" required>
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
                                <option value="{{ $key }}" {{ old('facility_type') == $key ? 'selected' : '' }}>
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
                                id="location" name="location" value="{{ old('location') }}" required>
                            @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="capacity" class="form-label">Capacity</label>
                            <input type="number" class="form-control @error('capacity') is-invalid @enderror"
                                id="capacity" name="capacity" value="{{ old('capacity', 0) }}" min="0">
                            @error('capacity')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Maximum number of people or projects this facility can accommodate.</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                            id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Provide a detailed description of the facility's purpose and capabilities.</div>
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
                                            {{ in_array($key, old('capabilities', [])) ? 'checked' : '' }}>
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
                            <div class="form-text">Select capabilities that this facility provides.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="availability_status" class="form-label">Availability Status</label>
                            <select class="form-select @error('availability_status') is-invalid @enderror"
                                id="availability_status" name="availability_status">
                                <option value="available" {{ old('availability_status', 'available') == 'available' ? 'selected' : '' }}>Available</option>
                                <option value="maintenance" {{ old('availability_status') == 'maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                                <option value="unavailable" {{ old('availability_status') == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                            </select>
                            @error('availability_status')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Equipment List (Optional) -->
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
                        <input type="hidden" name="equipment_list" id="equipmentListInput" value="[]">
                        <div class="form-text">Add equipment available at this facility (optional).</div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('facilities.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Create Facility
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
                <h6>Facility Types</h6>
                <ul class="list-unstyled small text-muted">
                    <li><strong>Laboratory:</strong> Research and testing facilities</li>
                    <li><strong>Workshop:</strong> Hands-on manufacturing and assembly</li>
                    <li><strong>Office:</strong> Administrative and planning spaces</li>
                    <li><strong>Manufacturing:</strong> Production and fabrication</li>
                    <li><strong>Storage:</strong> Equipment and material storage</li>
                    <li><strong>Testing:</strong> Quality assurance and validation</li>
                    <li><strong>Research:</strong> Academic and industry research</li>
                </ul>

                <h6 class="mt-3">Capability Examples</h6>
                <ul class="list-unstyled small text-muted">
                    <li><strong>CNC Machining:</strong> Computer numerical control</li>
                    <li><strong>3D Printing:</strong> Additive manufacturing</li>
                    <li><strong>Laser Cutting:</strong> Precision cutting</li>
                    <li><strong>Welding:</strong> Metal joining processes</li>
                    <li><strong>Assembly:</strong> Product assembly lines</li>
                    <li><strong>Testing:</strong> Quality control testing</li>
                </ul>
            </div>
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
        const form = document.querySelector('form');

        let equipment = [];

        // Add equipment
        addEquipmentBtn.addEventListener('click', function() {
            addEquipment();
        });

        equipmentInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addEquipment();
            }
        });

        function addEquipment() {
            const equipmentName = equipmentInput.value.trim();
            if (equipmentName && !equipment.includes(equipmentName)) {
                equipment.push(equipmentName);
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
            equipment.forEach((item, index) => {
                const badge = document.createElement('span');
                badge.className = 'badge bg-secondary me-2 mb-2';
                badge.innerHTML = `
                    ${item}
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
            const capabilities = document.querySelectorAll('input[name="capabilities[]"]:checked');
            const equipmentCount = equipment.length;

            if (equipmentCount > 0 && capabilities.length === 0) {
                e.preventDefault();
                alert('Please select at least one capability when equipment is listed.');
                return false;
            }
        });

        // Make removeEquipment function global
        window.removeEquipment = removeEquipment;
    });
</script>
@endpush