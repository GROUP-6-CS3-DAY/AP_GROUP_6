@extends('layouts.app')

@section('title', 'Create Participant - InnoTrack')

@section('content')

<!-- Add error display at the top -->
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <h6 class="alert-heading">Please fix the following errors:</h6>
    <ul class="mb-0">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-user-plus me-2"></i>Add New Participant
            </h1>
            <a href="{{ route('participants.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Participants
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user me-2"></i>Participant Information
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('participants.store') }}" method="POST" id="participantForm">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                                id="full_name" name="full_name" value="{{ old('full_name') }}" required>
                            @error('full_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="affiliation" class="form-label">Affiliation <span class="text-danger">*</span></label>
                            <select class="form-select @error('affiliation') is-invalid @enderror"
                                id="affiliation" name="affiliation" required>
                                <option value="">Select Affiliation</option>
                                @foreach($affiliations as $key => $value)
                                <option value="{{ $key }}" {{ old('affiliation') == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                                @endforeach
                            </select>
                            @error('affiliation')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="specialization" class="form-label">Specialization</label>
                            <select class="form-select @error('specialization') is-invalid @enderror"
                                id="specialization" name="specialization">
                                <option value="">Select Specialization (Optional)</option>
                                @foreach($specializations as $key => $value)
                                <option value="{{ $key }}" {{ old('specialization') == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                                @endforeach
                            </select>
                            @error('specialization')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Optional, but required if cross-skill training is enabled.</div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="institution" class="form-label">Institution <span class="text-danger">*</span></label>
                            <select class="form-select @error('institution') is-invalid @enderror"
                                id="institution" name="institution" required>
                                <option value="">Select Institution</option>
                                @foreach($institutions as $key => $value)
                                <option value="{{ $key }}" {{ old('institution') == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                                @endforeach
                            </select>
                            @error('institution')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="project_id" class="form-label">Assign to Project</label>
                            <select class="form-select @error('project_id') is-invalid @enderror"
                                id="project_id" name="project_id">
                                <option value="">No project assignment</option>
                                @foreach($projects as $project)
                                <option value="{{ $project->getId() }}" {{ old('project_id') == $project->getId() ? 'selected' : '' }}>
                                    {{ $project->getTitle() }}
                                </option>
                                @endforeach
                            </select>
                            @error('project_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Optional project assignment during creation.</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input @error('cross_skill_trained') is-invalid @enderror"
                                type="checkbox" role="switch" name="cross_skill_trained" value="1" id="cross_skill_trained"
                                {{ old('cross_skill_trained') ? 'checked' : '' }}>
                            <label class="form-check-label" for="cross_skill_trained">
                                Cross Skill Trained
                            </label>
                            @error('cross_skill_trained')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Check if the participant has cross-functional training across multiple domains. Requires specialization to be selected.</div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('participants.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-user-plus me-1"></i>Add Participant
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
                <h6>Affiliations</h6>
                <ul class="list-unstyled small text-muted">
                    @foreach($affiliations as $key => $value)
                    <li><strong>{{ $value }}:</strong> {{ $key == 'cs' ? 'Computer Science students and professionals' : 
                        ($key == 'ee' ? 'Electrical Engineering focused individuals' : 
                        ($key == 'me' ? 'Mechanical Engineering disciplines' : 
                        ($key == 'ce' ? 'Civil Engineering professionals' : 'Other disciplines or interdisciplinary'))) }}</li>
                    @endforeach
                </ul>

                <h6 class="mt-3">Specializations</h6>
                <ul class="list-unstyled small text-muted">
                    @foreach($specializations as $key => $value)
                    <li><strong>{{ $value }}:</strong> {{ $key == 'software' ? 'Programming and software development' : 
                        ($key == 'hardware' ? 'Electronics and physical systems' : 
                        ($key == 'research' ? 'Research & Development activities' : 
                        ($key == 'testing' ? 'Testing & Quality Assurance' : 'Project Management'))) }}</li>
                    @endforeach
                </ul>

                <h6 class="mt-3">Institutions</h6>
                <ul class="list-unstyled small text-muted">
                    @foreach($institutions as $key => $value)
                    <li><strong>{{ $value }}:</strong> {{ $key == 'scit' ? 'School of Computing and IT' : 'Other institutions' }}</li>
                    @endforeach
                </ul>

                <div class="alert alert-info mt-3">
                    <small>
                        <i class="fas fa-lightbulb me-1"></i>
                        <strong>Business Rule:</strong> Cross-skill training requires a specialization to be selected first.
                    </small>
                </div>

                <div class="alert alert-warning mt-3">
                    <small>
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        <strong>Note:</strong> Email addresses must be unique across all participants.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('participantForm');
        const emailField = document.getElementById('email');
        const specializationField = document.getElementById('specialization');
        const crossSkillField = document.getElementById('cross_skill_trained');

        // Debug: Log form data before submission
        form.addEventListener('submit', function(e) {
            console.log('=== FORM SUBMISSION DEBUG ===');
            console.log('Full Name:', document.getElementById('full_name').value);
            console.log('Email:', emailField.value);
            console.log('Affiliation:', document.getElementById('affiliation').value);
            console.log('Institution:', document.getElementById('institution').value);
            console.log('Specialization:', specializationField.value);
            console.log('Cross Skill Trained:', crossSkillField.checked);
            console.log('Project ID:', document.getElementById('project_id').value);
            
            // Log form data as FormData would send it
            const formData = new FormData(form);
            console.log('FormData entries:');
            for (let [key, value] of formData.entries()) {
                console.log(`${key}: ${value}`);
            }
            console.log('=== END DEBUG ===');

            // Validate business rules
            let valid = true;
            let errors = [];

            // Check required fields
            const fullName = document.getElementById('full_name').value.trim();
            const email = emailField.value.trim();
            const affiliation = document.getElementById('affiliation').value;
            const institution = document.getElementById('institution').value;

            if (!fullName) {
                errors.push('Full name is required.');
                showFieldError(document.getElementById('full_name'), 'Full name is required.');
                valid = false;
            } else {
                clearFieldError(document.getElementById('full_name'));
            }

            if (!email) {
                errors.push('Email is required.');
                showFieldError(emailField, 'Email is required.');
                valid = false;
            } else if (!isValidEmail(email)) {
                errors.push('Please enter a valid email address.');
                showFieldError(emailField, 'Please enter a valid email address.');
                valid = false;
            } else {
                clearFieldError(emailField);
            }

            if (!affiliation) {
                errors.push('Affiliation is required.');
                showFieldError(document.getElementById('affiliation'), 'Affiliation is required.');
                valid = false;
            } else {
                clearFieldError(document.getElementById('affiliation'));
            }

            if (!institution) {
                errors.push('Institution is required.');
                showFieldError(document.getElementById('institution'), 'Institution is required.');
                valid = false;
            } else {
                clearFieldError(document.getElementById('institution'));
            }

            // Check cross-skill training business rule
            if (crossSkillField.checked && !specializationField.value) {
                errors.push('Specialization is required when cross-skill training is selected.');
                showFieldError(specializationField, 'Specialization is required when cross-skill training is selected.');
                valid = false;
            } else {
                clearFieldError(specializationField);
            }

            if (!valid) {
                e.preventDefault();
                console.error('Form validation failed:', errors);
                alert('Please fix the following errors:\n' + errors.join('\n'));
                return false;
            }

            console.log('Form validation passed, submitting...');
        });

        // Real-time validation
        crossSkillField.addEventListener('change', function() {
            validateCrossSkillSpecialization();
        });

        specializationField.addEventListener('change', function() {
            validateCrossSkillSpecialization();
        });

        emailField.addEventListener('blur', function() {
            validateEmail();
        });

        // Real-time field validation
        document.getElementById('full_name').addEventListener('blur', function() {
            if (!this.value.trim()) {
                showFieldError(this, 'Full name is required.');
            } else {
                clearFieldError(this);
            }
        });

        document.getElementById('affiliation').addEventListener('change', function() {
            if (!this.value) {
                showFieldError(this, 'Please select an affiliation.');
            } else {
                clearFieldError(this);
            }
        });

        document.getElementById('institution').addEventListener('change', function() {
            if (!this.value) {
                showFieldError(this, 'Please select an institution.');
            } else {
                clearFieldError(this);
            }
        });

        function validateCrossSkillSpecialization() {
            if (crossSkillField.checked && !specializationField.value) {
                showFieldError(specializationField, 'Specialization is required when cross-skill training is selected.');
                return false;
            } else {
                clearFieldError(specializationField);
                return true;
            }
        }

        function validateEmail() {
            const email = emailField.value.trim();
            if (email && !isValidEmail(email)) {
                showFieldError(emailField, 'Please enter a valid email address.');
                return false;
            } else {
                clearFieldError(emailField);
                return true;
            }
        }

        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        function showFieldError(field, message) {
            field.classList.add('is-invalid');
            // Remove any existing custom feedback first
            clearFieldError(field);
            // Add new feedback
            let feedback = document.createElement('div');
            feedback.className = 'invalid-feedback js-validation-error';
            feedback.textContent = message;
            field.parentNode.appendChild(feedback);
        }

        function clearFieldError(field) {
            field.classList.remove('is-invalid');
            // Only remove JavaScript-generated feedback, not server-side validation errors
            const jsErrors = field.parentNode.querySelectorAll('.js-validation-error');
            jsErrors.forEach(error => error.remove());
        }
    });
</script>
@endpush

@push('styles')
<style>
    .form-check-input:checked {
        background-color: #28a745;
        border-color: #28a745;
    }
    
    .alert {
        font-size: 0.875em;
    }
    
    .form-text {
        font-size: 0.8em;
        color: #6c757d;
    }
</style>
@endpush