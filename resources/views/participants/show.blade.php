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
