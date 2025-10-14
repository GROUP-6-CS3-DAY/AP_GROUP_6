@extends('layouts.app')

@section('title', 'Edit ' . $participant->getFullName() . ' - InnoTrack')

@section('content')
@php
use Illuminate\Support\Str;
@endphp

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

<div class="row g-4 align-items-start">
    <div class="col-lg-8 d-flex">
        <div class="card w-100 h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user me-2"></i>Edit Participant Information
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('participants.update', $participant->getId()) }}" method="POST" id="participantForm">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                                id="full_name" name="full_name" value="{{ old('full_name', $participant->getFullName()) }}" required>
                            @error('full_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                id="email" name="email" value="{{ old('email', $participant->getEmail()) }}" required>
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
                                <option value="{{ $key }}" {{ old('affiliation', $participant->getAffiliation()->getValue()) == $key ? 'selected' : '' }}>
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
                                <option value="{{ $key }}" {{ old('specialization', $participant->getSpecialization()?->getValue()) == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                                @endforeach
                            </select>
                            @error('specialization')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="institution" class="form-label">Institution <span class="text-danger">*</span></label>
                            <select class="form-select @error('institution') is-invalid @enderror"
                                id="institution" name="institution" required>
                                <option value="">Select Institution</option>
                                @foreach($institutions as $key => $value)
                                <option value="{{ $key }}" {{ old('institution', $participant->getInstitution()) == $key ? 'selected' : '' }}>
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
                                    <option value="{{ $project->getId() }}" 
                                        {{ (old('project_id') ?? $participant->getProjectId()) == $project->getId() ? 'selected' : '' }}>
                                        {{ $project->getTitle() }}
                                    </option>
                                @endforeach
                            </select>
                            @error('project_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input @error('cross_skill_trained') is-invalid @enderror" 
                                type="checkbox" role="switch" id="cross_skill_trained" name="cross_skill_trained" 
                                value="1" {{ old('cross_skill_trained', $participant->isCrossSkillTrained()) ? 'checked' : '' }}>
                            <label class="form-check-label" for="cross_skill_trained">
                                Cross Skill Trained
                            </label>
                        </div>
                        @error('cross_skill_trained')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Check if the participant has completed cross-skill training. Required when specialization is specified.</div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('participants.show', $participant->getId()) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save me-1"></i>Update Participant
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4 d-flex flex-column gap-4">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Current Information
                </h6>
            </div>
            <div class="card-body">
                <h6>Participant Details</h6>
                <ul class="list-unstyled small text-muted mb-0">
                    <li><strong>Name:</strong> {{ $participant->getFullName() }}</li>
                    <li><strong>Email:</strong> {{ $participant->getEmail() }}</li>
                    <li><strong>Affiliation:</strong> {{ $participant->getAffiliation()->getDisplayName() }}</li>
                    <li><strong>Institution:</strong> {{ strtoupper($participant->getInstitution()) }}</li>
                    @if($participant->hasSpecialization())
                    <li><strong>Specialization:</strong> {{ $participant->getSpecialization()->getDisplayName() }}</li>
                    @endif
                    <li><strong>Cross Skill Trained:</strong> {{ $participant->isCrossSkillTrained() ? 'Yes' : 'No' }}</li>
                    <li><strong>Project Assignment:</strong> {{ $participant->isAssignedToProject() ? 'Assigned' : 'Unassigned' }}</li>
                </ul>
            </div>
        </div>

        @if($participant->isAssignedToProject())
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-project-diagram me-2"></i>Current Project
                </h6>
            </div>
            <div class="card-body">
                @php
                    $assignedProject = collect($projects)->firstWhere(fn($p) => $p->getId() === $participant->getProjectId());
                @endphp
                @if($assignedProject)
                <h6>{{ $assignedProject->getTitle() }}</h6>
                <p class="small text-muted mb-3">{{ Str::limit($assignedProject->getDescription(), 100) }}</p>
                <a href="{{ route('projects.show', $assignedProject->getId()) }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-eye me-1"></i>View Project
                </a>
                @else
                <div class="alert alert-warning">
                    <small>Project assignment exists but project not found.</small>
                </div>
                @endif
            </div>
        </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-question-circle me-2"></i>Help
                </h6>
            </div>
            <div class="card-body small text-muted">
                <h6>Business Rules</h6>
                <ul class="list-unstyled mb-3">
                    <li><i class="fas fa-check text-success me-1"></i>Cross-skill training is required when specialization is specified</li>
                    <li><i class="fas fa-check text-success me-1"></i>Email must be unique across all participants</li>
                    <li><i class="fas fa-info text-info me-1"></i>Participants can be assigned to one project at a time</li>
                </ul>
                
                <h6>Field Descriptions</h6>
                <ul class="list-unstyled mb-0">
                    <li><strong>Affiliation:</strong> Academic or professional background</li>
                    <li><strong>Specialization:</strong> Primary skill area (optional)</li>
                    <li><strong>Cross-Skill:</strong> Ability to work across domains</li>
                    <li><strong>Institution:</strong> Organizational affiliation</li>
                </ul>
            </div>
        </div>

        <div class="alert alert-warning mb-0">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Note:</strong> Changing participant details may affect project assignments and collaborations.
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
            console.log('Form submission data:');
            console.log('Full Name:', document.getElementById('full_name').value);
            console.log('Email:', emailField.value);
            console.log('Affiliation:', document.getElementById('affiliation').value);
            console.log('Institution:', document.getElementById('institution').value);
            console.log('Specialization:', specializationField.value);
            console.log('Cross Skill Trained:', crossSkillField.checked);
            console.log('Project ID:', document.getElementById('project_id').value);
            
            // Validate business rules
            let valid = true;
            let errors = [];

            // Check if cross-skill training requires specialization
            if (crossSkillField.checked && !specializationField.value) {
                errors.push('Specialization is required when cross-skill training is selected.');
                showFieldError(specializationField, 'Specialization is required when cross-skill training is selected.');
                valid = false;
            } else {
                clearFieldError(specializationField);
            }

            // Email validation
            const email = emailField.value.trim();
            if (email && !isValidEmail(email)) {
                errors.push('Please enter a valid email address.');
                showFieldError(emailField, 'Please enter a valid email address.');
                valid = false;
            } else {
                clearFieldError(emailField);
            }

            if (!valid) {
                e.preventDefault();
                alert('Please fix the following errors:\n' + errors.join('\n'));
                return false;
            }
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
            let feedback = field.parentNode.querySelector('.invalid-feedback');
            if (!feedback) {
                feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                field.parentNode.appendChild(feedback);
            }
            feedback.textContent = message;
        }

        function clearFieldError(field) {
            field.classList.remove('is-invalid');
            const feedback = field.parentNode.querySelector('.invalid-feedback');
            if (feedback) {
                feedback.remove();
            }
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
    .card.h-100 {
        display: flex;
        flex-direction: column;
    }
</style>
@endpush