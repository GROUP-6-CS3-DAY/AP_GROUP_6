@extends('layouts.app')

@section('content')
<div class="container-fluid px-0">
    <!-- Banner -->
    <div style="background: #48284A; height: 157px; width: 100%;"></div>
</div>
<div class="container mt-n4">
    <!-- Tab-like header section -->
    <div class="d-flex align-items-center mb-4" style="margin-top: -40px;">
        <div class="bg-white rounded-pill px-4 py-2 shadow-sm" style="font-size: 1.5rem; font-weight: 600; color: #EFF1F3">
            Participant Details
        </div>
    </div>

    <div class="card shadow-sm" style="border: none; border-radius: 10px;">
        <div class="card-body p-4">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-3" style="padding-top: 20px; padding-left: 20px;">
                            <i class="fas fa-user" style="color: #A1869E; width: 24px;"></i>
                            <span class="ms-3">
                                <span style="color: #48284A; font-weight: 600; margin-right: 10px;">Full Name:</span>
                                {{ $participant->full_name }}
                            </span>
                        </div>

                        <div class="d-flex align-items-center mb-3" style="padding-top: 20px; padding-left: 20px;">
                            <i class="fas fa-envelope" style="color: #A1869E; width: 24px;"></i>
                            <span class="ms-3">
                                <span style="color: #48284A; font-weight: 600; margin-right: 10px;">Email:</span>
                                {{ $participant->email }}
                            </span>
                        </div>
                        
                        <div class="d-flex align-items-center mb-3"style="padding-top: 20px; padding-left: 20px;">
                            <i class="fas fa-users" style="color: #A1869E; width: 24px;"></i>
                            <span class="ms-3">
                                <span style="color: #48284A; font-weight: 600; margin-right: 10px;">Affiliation:</span>
                                {{ ucfirst($participant->affiliation) }}
                            </span>
                        </div>
                        
                        <div class="d-flex align-items-center mb-3" style="padding-top: 20px; padding-left: 20px;">
                            <i class="fas fa-code-branch" style="color: #A1869E; width: 24px;"></i>
                            <span class="ms-3">
                                <span style="color: #48284A; font-weight: 600; margin-right: 10px;">Specialization:</span>
                                {{ ucfirst($participant->specialization) }}
                            </span>
                        </div>

                        <div class="d-flex align-items-center mb-3" style="padding-top: 20px; padding-left: 20px;">
                            <i class="fas fa-graduation-cap" style="color: #A1869E; width: 24px;"></i>
                            <span class="ms-3">
                                <span style="color: #48284A; font-weight: 600; margin-right: 10px;">Institution:</span>
                                {{ strtoupper($participant->institution) }}
                            </span>
                        </div>

                        <div class="d-flex align-items-center" style="padding-top: 20px; padding-left: 20px;">
                            <i class="fas fa-check-circle" style="color: #A1869E; width: 24px;"></i>
                            <span class="ms-3">
                                <span style="color: #48284A; font-weight: 600; margin-right: 10px;">Cross Skill Trained:</span>
                                <span class="badge {{ $participant->cross_skill_trained ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $participant->cross_skill_trained ? 'Yes' : 'No' }}
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Add Projects Section -->
                <div class="col-md-6" style="padding-left: 20px; padding-top: 20px;">
                    <!-- Add Statistics Card -->
                    <div class="card shadow-sm mb-3" style="border: none; border-radius: 10px; background-color: #f8f9fa;">
                        <div class="card-body">
                            <h3 style="color: #48284A; font-weight: 600;">Project Statistics</h3>
                            <div class="row mt-3">
                                <div class="col-4 text-center">
                                    <h3 style="color: #A1869E;">{{ $participant->projects->count() }}</h3>
                                    <small>Total Projects</small>
                                </div>
                                <div class="col-4 text-center">
                                    <h3 style="color: #A1869E;">
                                        {{ $participant->projects->where('pivot.role_on_project', 'lead')->count() }}
                                    </h3>
                                    <small>As Lead</small>
                                </div>
                                <div class="col-4 text-center">
                                    <h3 style="color: #A1869E;">
                                        {{ $participant->projects->where('pivot.role_on_project', 'member')->count() }}
                                    </h3>
                                    <small>As Member</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Existing Projects Card -->
                    <div class="card shadow-sm" style="border: none; border-radius: 10px; padding-top: 20px;">
                        <div class="card-body p-4">
                            <h4 style="color: #48284A; font-weight: 600;">Assigned Projects</h4>
                            
                            @if($participant->projects->count() > 0)
                                <div class="list-group mt-3">
                                    @foreach($participant->projects as $project)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-0">{{ $project->name }}</h6>
                                                <small>Role: {{ ucfirst($project->pivot->role_on_project) }}</small>
                                                <br>
                                                <small>Skill: {{ ucfirst($project->pivot->skill_role) }}</small>
                                            </div>
                                            <form action="{{ route('participants.remove-project', [$participant->participant_id, $project->project_id]) }}" 
                                                  method="POST" style="display:inline;">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted">No projects assigned yet.</p>
                            @endif

                            <!-- Add Project Form -->
                            <form action="{{ route('participants.add-project', $participant->participant_id) }}" 
                                  method="POST" class="mt-4">
                                @csrf
                                <div class="mb-3" >
                                    <label class="form-label" style="color: #48284A;">Add to Project</label>
                                    <select name="project_id" class="form-select" required>
                                        @foreach($availableProjects as $project)
                                            <option value="{{ $project->project_id }}">{{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3" style="padding-top: 20px;">
                                    <label class="form-label" style="color: #48284A;">Role on Project</label>
                                    <select name="role_on_project" class="form-select" required>
                                        <option value="lead">Lead</option>
                                        <option value="member">Member</option>
                                        <option value="consultant">Consultant</option>
                                    </select>
                                </div>
                                <div class="mb-3" style="padding-top: 20px; padding-bottom: 20px;">
                                    <label class="form-label" style="color: #48284A;">Skill Role</label>
                                    <select name="skill_role" class="form-select" required>
                                        <option value="software">Software</option>
                                        <option value="hardware">Hardware</option>
                                        <option value="business">Business</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn" 
                                        style="background: #A1869E; color: white; border-radius: 3px; border-width: 0cm;">
                                    Add to Project
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4" style="padding-top: 20px; padding-left: 20px;">
        <a href="{{ route('participants.index') }}" 
           class="btn" 
           style="background: #A1869E; 
                  color: white; 
                  padding: 10px 30px; 
                  border-radius: 5px; 
                  text-decoration: none;">
            Back to List
        </a>
    </div>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endsection
