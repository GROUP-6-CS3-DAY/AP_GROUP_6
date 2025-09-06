@extends('layouts.app')

@section('title', 'Create Service - InnoTrack')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-plus me-2"></i>Create New Service
            </h1>
            <a href="{{ route('services.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Services
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-cogs me-2"></i>Service Information
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('services.store') }}" method="POST">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Service Name <span class="text-danger">*</span></label>
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
                            <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                            <select class="form-select @error('category') is-invalid @enderror"
                                id="category" name="category" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $key => $value)
                                <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                                @endforeach
                            </select>
                            @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="skill_type" class="form-label">Skill Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('skill_type') is-invalid @enderror"
                                id="skill_type" name="skill_type" required>
                                <option value="">Select Skill Type</option>
                                @foreach($skillTypes as $key => $value)
                                <option value="{{ $key }}" {{ old('skill_type') == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                                @endforeach
                            </select>
                            @error('skill_type')
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
                        <div class="form-text">Provide a detailed description of what this service offers and how it works.</div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('services.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-1"></i>Create Service
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
                <h6>Service Categories</h6>
                <ul class="list-unstyled small text-muted">
                    <li><strong>Machining:</strong> CNC, cutting, drilling operations</li>
                    <li><strong>Testing:</strong> Quality assurance and validation</li>
                    <li><strong>Training:</strong> Educational programs and workshops</li>
                    <li><strong>Prototyping:</strong> Rapid development and testing</li>
                    <li><strong>Fabrication:</strong> Building and assembly services</li>
                    <li><strong>Analysis:</strong> Data processing and evaluation</li>
                    <li><strong>Consultation:</strong> Expert advice and planning</li>
                </ul>

                <h6 class="mt-3">Skill Types</h6>
                <ul class="list-unstyled small text-muted">
                    <li><strong>Hardware:</strong> Physical equipment and tools</li>
                    <li><strong>Software:</strong> Digital tools and applications</li>
                    <li><strong>Integration:</strong> System connectivity and coordination</li>
                    <li><strong>Business:</strong> Strategic planning and management</li>
                    <li><strong>Research:</strong> Investigation and development</li>
                </ul>

                <div class="alert alert-info mt-3">
                    <i class="fas fa-lightbulb me-2"></i>
                    <strong>Tip:</strong> Service names must be unique within each facility. Choose descriptive names that clearly indicate what the service provides.
                </div>
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

        form.addEventListener('submit', function(e) {
            const facilityId = document.getElementById('facility_id').value;
            const name = document.getElementById('name').value;

            if (!facilityId) {
                e.preventDefault();
                alert('Please select a facility for this service.');
                return false;
            }

            if (!name.trim()) {
                e.preventDefault();
                alert('Please enter a service name.');
                return false;
            }
        });
    });
</script>
@endpush