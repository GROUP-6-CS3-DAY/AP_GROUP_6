@extends('layouts.app')

@section('title', 'Create Participant - InnoTrack')

@section('content')
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
                <form action="{{ route('participants.store') }}" method="POST">
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
                                <option value="cs" {{ old('affiliation') == 'cs' ? 'selected' : '' }}>Computer Science</option>
                                <option value="se" {{ old('affiliation') == 'se' ? 'selected' : '' }}>Software Engineering</option>
                                <option value="engineering" {{ old('affiliation') == 'engineering' ? 'selected' : '' }}>Engineering</option>
                                <option value="other" {{ old('affiliation') == 'other' ? 'selected' : '' }}>Other</option>
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
                                <option value="software" {{ old('specialization') == 'software' ? 'selected' : '' }}>Software</option>
                                <option value="hardware" {{ old('specialization') == 'hardware' ? 'selected' : '' }}>Hardware</option>
                                <option value="business" {{ old('specialization') == 'business' ? 'selected' : '' }}>Business</option>
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
                                <option value="scit" {{ old('institution') == 'scit' ? 'selected' : '' }}>SCIT</option>
                                <option value="cedat" {{ old('institution') == 'cedat' ? 'selected' : '' }}>CEDAT</option>
                                <option value="unipod" {{ old('institution') == 'unipod' ? 'selected' : '' }}>UniPod</option>
                                <option value="uiri" {{ old('institution') == 'uiri' ? 'selected' : '' }}>UIRI</option>
                                <option value="lwera" {{ old('institution') == 'lwera' ? 'selected' : '' }}>Lwera</option>
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
                                @if(isset($projects))
    @foreach($projects as $project)
        <option value="{{ $project->project_id }}" {{ old('project_id') == $project->project_id ? 'selected' : '' }}>
            {{ $project->title }}
        </option>
    @endforeach
@endif
                            </select>
                            @error('project_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input @error('cross_skill_trained') is-invalid @enderror"
                                type="checkbox" name="cross_skill_trained" value="1" id="cross_skill_trained"
                                {{ old('cross_skill_trained') ? 'checked' : '' }}>
                            <label class="form-check-label" for="cross_skill_trained">
                                Cross Skill Trained
                            </label>
                            @error('cross_skill_trained')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Check if the participant has cross-functional training across multiple domains.</div>
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
                    <li><strong>Computer Science:</strong> CS students and professionals</li>
                    <li><strong>Software Engineering:</strong> SE focused individuals</li>
                    <li><strong>Engineering:</strong> General engineering disciplines</li>
                    <li><strong>Other:</strong> Non-technical or interdisciplinary</li>
                </ul>

                <h6 class="mt-3">Specializations</h6>
                <ul class="list-unstyled small text-muted">
                    <li><strong>Software:</strong> Programming and software development</li>
                    <li><strong>Hardware:</strong> Electronics and physical systems</li>
                    <li><strong>Business:</strong> Entrepreneurship and business development</li>
                </ul>

                <h6 class="mt-3">Institutions</h6>
                <ul class="list-unstyled small text-muted">
                    <li><strong>SCIT:</strong> School of Computing and IT</li>
                    <li><strong>CEDAT:</strong> College of Engineering</li>
                    <li><strong>UniPod:</strong> University Innovation Hub</li>
                    <li><strong>UIRI:</strong> Uganda Industrial Research Institute</li>
                    <li><strong>Lwera:</strong> Lwera Electronics Laboratory</li>
                </ul>

                <div class="alert alert-info mt-3">
                    <small>
                        <i class="fas fa-lightbulb me-1"></i>
                        <strong>Tip:</strong> Cross-skill training indicates participants who can work across different domains (software/hardware/business).
                    </small>
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
    });
</script>
@endpush