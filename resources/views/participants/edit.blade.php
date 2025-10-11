@extends('layouts.app')

@section('title', 'Edit ' . $participant->full_name . ' - InnoTrack')

@section('content')
@php
use Illuminate\Support\Str;
@endphp
<div class="row g-4 align-items-start"> <!-- changed row -->
    <div class="col-lg-8 d-flex"> <!-- added d-flex -->
        <div class="card w-100 h-100"> <!-- full height card -->
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user me-2"></i>Edit Participant Information
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('participants.update', $participant) }}" method="POST" id="participantForm">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                                id="full_name" name="full_name" value="{{ old('full_name', $participant->full_name) }}" required>
                            @error('full_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                id="email" name="email" value="{{ old('email', $participant->email) }}" required>
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
                                <option value="cs" {{ old('affiliation', $participant->affiliation) == 'cs' ? 'selected' : '' }}>Computer Science</option>
                                <option value="se" {{ old('affiliation', $participant->affiliation) == 'se' ? 'selected' : '' }}>Software Engineering</option>
                                <option value="engineering" {{ old('affiliation', $participant->affiliation) == 'engineering' ? 'selected' : '' }}>Engineering</option>
                                <option value="other" {{ old('affiliation', $participant->affiliation) == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('affiliation')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="specialization" class="form-label">Specialization <span class="text-danger">*</span></label>
                            <select class="form-select @error('specialization') is-invalid @enderror"
                                id="specialization" name="specialization" required>
                                <option value="">Select Specialization</option>
                                <option value="software" {{ old('specialization', $participant->specialization) == 'software' ? 'selected' : '' }}>Software</option>
                                <option value="hardware" {{ old('specialization', $participant->specialization) == 'hardware' ? 'selected' : '' }}>Hardware</option>
                                <option value="business" {{ old('specialization', $participant->specialization) == 'business' ? 'selected' : '' }}>Business</option>
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
                                <option value="scit" {{ old('institution', $participant->institution) == 'scit' ? 'selected' : '' }}>SCIT</option>
                                <option value="cedat" {{ old('institution', $participant->institution) == 'cedat' ? 'selected' : '' }}>CEDAT</option>
                                <option value="unipod" {{ old('institution', $participant->institution) == 'unipod' ? 'selected' : '' }}>UniPod</option>
                                <option value="uiri" {{ old('institution', $participant->institution) == 'uiri' ? 'selected' : '' }}>UIRI</option>
                                <option value="lwera" {{ old('institution', $participant->institution) == 'lwera' ? 'selected' : '' }}>Lwera</option>
                            </select>
                            @error('institution')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Select the institution or organization.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="project_id" class="form-label">Assign to Project</label>
                            <select class="form-select @error('project_id') is-invalid @enderror"
                                id="project_id" name="project_id">
                                <option value="">No project assignment</option>
                                @if(isset($projects))
                                    @foreach($projects as $project)
                                        <option value="{{ $project->project_id }}" 
                                            {{ (old('project_id') ?? $participant->project_id ?? '') == $project->project_id ? 'selected' : '' }}>
                                            {{ $project->title }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('project_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Change project assignment if needed.</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input @error('cross_skill_trained') is-invalid @enderror" 
                                type="checkbox" role="switch" id="cross_skill_trained" name="cross_skill_trained" 
                                value="1" {{ old('cross_skill_trained', $participant->cross_skill_trained) ? 'checked' : '' }}>
                            <label class="form-check-label" for="cross_skill_trained">
                                Cross Skill Trained
                            </label>
                        </div>
                        @error('cross_skill_trained')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Check if the participant has completed cross-skill training.</div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('participants.show', $participant) }}" class="btn btn-outline-secondary">
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

    <div class="col-lg-4 d-flex flex-column gap-4"> <!-- right column stacked -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Current Information
                </h6>
            </div>
            <div class="card-body">
                <h6>Participant Details</h6>
                <ul class="list-unstyled small text-muted mb-0">
                    <li><strong>Name:</strong> {{ $participant->full_name }}</li>
                    <li><strong>Email:</strong> {{ $participant->email }}</li>
                    <li><strong>Affiliation:</strong> {{ ucfirst($participant->affiliation) }}</li>
                    <li><strong>Specialization:</strong> {{ ucfirst($participant->specialization) }}</li>
                    <li><strong>Institution:</strong> {{ strtoupper($participant->institution) }}</li>
                    <li><strong>Cross Skill Trained:</strong> {{ $participant->cross_skill_trained ? 'Yes' : 'No' }}</li>
                    <li><strong>Created:</strong> {{ $participant->created_at->format('M d, Y') }}</li>
                    <li><strong>Updated:</strong> {{ $participant->updated_at->format('M d, Y') }}</li>
                </ul>
            </div>
        </div>
        @if($participant->project)
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-project-diagram me-2"></i>Current Project
                </h6>
            </div>
            <div class="card-body">
                <h6>{{ $participant->project->name }}</h6>
                <ul class="list-unstyled small text-muted mb-3">
                    <li><strong>Status:</strong> {{ ucfirst($participant->project->status ?? 'Active') }}</li>
                    <li><strong>Description:</strong> {{ Str::limit($participant->project->description ?? 'N/A', 100) }}</li>
                </ul>
                <a href="{{ route('projects.show', $participant->project) }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-eye me-1"></i>View Project
                </a>
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
                <h6>Affiliations</h6>
                <ul class="list-unstyled mb-3">
                    <li><strong>Computer Science:</strong> CS students and professionals</li>
                    <li><strong>Software Engineering:</strong> SE focused individuals</li>
                    <li><strong>Engineering:</strong> General engineering disciplines</li>
                    <li><strong>Other:</strong> Non-technical or interdisciplinary</li>
                </ul>
                <h6>Specializations</h6>
                <ul class="list-unstyled mb-3">
                    <li><strong>Software:</strong> Programming and software development</li>
                    <li><strong>Hardware:</strong> Electronics and physical systems</li>
                    <li><strong>Business:</strong> Entrepreneurship and business development</li>
                </ul>
                <h6>Institutions</h6>
                <ul class="list-unstyled mb-0">
                    <li><strong>SCIT:</strong> School of Computing and IT</li>
                    <li><strong>CEDAT:</strong> College of Engineering</li>
                    <li><strong>UniPod:</strong> University Innovation Hub</li>
                    <li><strong>UIRI:</strong> Uganda Industrial Research Institute</li>
                    <li><strong>Lwera:</strong> Lwera Electronics Laboratory</li>
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

        // Email validation
        emailField.addEventListener('blur', function() {
            const email = this.value.trim();
            if (email && !isValidEmail(email)) {
                this.classList.add('is-invalid');
                let feedback = this.parentNode.querySelector('.invalid-feedback');
                if (!feedback) {
                    feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback';
                    this.parentNode.appendChild(feedback);
                }
                feedback.textContent = 'Please enter a valid email address.';
            } else {
                this.classList.remove('is-invalid');
            }
        });

        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        // Form validation
        form.addEventListener('submit', function(e) {
            const fullName = document.getElementById('full_name').value;
            const email = document.getElementById('email').value;
            const affiliation = document.getElementById('affiliation').value;
            const specialization = document.getElementById('specialization').value;
            const institution = document.getElementById('institution').value;

            if (!fullName.trim()) {
                e.preventDefault();
                alert('Please enter the participant\'s full name.');
                return false;
            }

            if (!email.trim() || !isValidEmail(email.trim())) {
                e.preventDefault();
                alert('Please enter a valid email address.');
                return false;
            }

            if (!affiliation) {
                e.preventDefault();
                alert('Please select an affiliation.');
                return false;
            }

            if (!specialization) {
                e.preventDefault();
                alert('Please select a specialization.');
                return false;
            }

            if (!institution) {
                e.preventDefault();
                alert('Please select an institution.');
                return false;
            }
        });
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