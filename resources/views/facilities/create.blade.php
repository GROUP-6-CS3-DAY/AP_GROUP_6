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
                            <label for="partner_organization" class="form-label">Partner Organization <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('partner_organization') is-invalid @enderror"
                                id="partner_organization" name="partner_organization"
                                value="{{ old('partner_organization') }}" required>
                            @error('partner_organization')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror"
                                id="location" name="location" value="{{ old('location') }}" required>
                            @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
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
                        <div class="form-text">Provide a detailed description of the facility's purpose and capabilities.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Capabilities <span class="text-danger">*</span></label>
                        <div class="row">
                            @foreach($capabilities as $key => $value)
                            <div class="col-md-4 mb-2">
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
                        <div class="form-text">Select all capabilities that this facility provides.</div>
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
                    <li><strong>Workshop:</strong> Hands-on manufacturing and assembly</li>
                    <li><strong>Laboratory:</strong> Research and testing facilities</li>
                    <li><strong>Testing Center:</strong> Quality assurance and validation</li>
                    <li><strong>Maker Space:</strong> Creative prototyping and innovation</li>
                    <li><strong>Innovation Hub:</strong> Technology development center</li>
                    <li><strong>Research Center:</strong> Academic and industry research</li>
                </ul>

                <h6 class="mt-3">Partner Organizations</h6>
                <ul class="list-unstyled small text-muted">
                    <li><strong>UniPod:</strong> University innovation hub</li>
                    <li><strong>UIRI:</strong> Uganda Industrial Research Institute</li>
                    <li><strong>Lwera Lab:</strong> Specialized electronics facility</li>
                    <li><strong>SCIT:</strong> School of Computing and IT</li>
                    <li><strong>CEDAT:</strong> College of Engineering</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Form validation enhancement
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const capabilities = document.querySelectorAll('input[name="capabilities[]"]');

        form.addEventListener('submit', function(e) {
            const checkedCapabilities = document.querySelectorAll('input[name="capabilities[]"]:checked');

            if (checkedCapabilities.length === 0) {
                e.preventDefault();
                alert('Please select at least one capability for the facility.');
                return false;
            }
        });
    });
</script>
@endpush