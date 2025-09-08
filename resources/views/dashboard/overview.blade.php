@extends('layouts.app')

@section('title', 'Dashboard - InnoTrack')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-tachometer-alt me-2"></i>Dashboard Overview
            </h1>
            <div>
                <a href="{{ route('facilities.create') }}" class="btn btn-primary me-2">
                    <i class="fas fa-plus me-1"></i>New Facility
                </a>
                <a href="{{ route('services.create') }}" class="btn btn-success me-2">
                    <i class="fas fa-plus me-1"></i>New Service
                </a>
                <a href="{{ route('equipment.create') }}" class="btn btn-info">
                    <i class="fas fa-plus me-1"></i>New Equipment
                </a>
                <a href="{{ route('participants.create') }}" class="btn btn-info" style="margin-left:10px;">
                    <i class="fas fa-plus me-1"></i>New Participant
                </a>
                <a href="{{ route('programs.create') }}" class="btn btn-info" style="background-color: #8d5aebff;margin:0px 10px;">
                    <i class="fas fa-plus me-1"></i>New Program
                </a>
                <a href="{{ route('projects.create') }}" class="btn btn-info" style="background-color: #42c15eff;margin:0px 0px;">
                    <i class="fas fa-plus me-1"></i>New Project
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $facilitiesCount ?? 0 }}</h4>
                        <p class="card-text">Total Facilities</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-building fa-2x"></i>
                    </div>
                </div>
                <a href="{{ route('facilities.index') }}" class="text-white text-decoration-none">
                    <small>View all facilities <i class="fas fa-arrow-right"></i></small>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $outcomesCount ?? 0 }}</h4>
                        <p class="card-text">Total Outcomes</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-trophy fa-2x"></i>
                    </div>
                </div>
                <a href="{{ route('outcomes.index') }}" class="text-white text-decoration-none">
                    <small>View all outcomes <i class="fas fa-arrow-right"></i></small>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $servicesCount ?? 0 }}</h4>
                        <p class="card-text">Total Services</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-cogs fa-2x"></i>
                    </div>
                </div>
                <a href="{{ route('services.index') }}" class="text-white text-decoration-none">
                    <small>View all services <i class="fas fa-arrow-right"></i></small>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $equipmentCount ?? 0 }}</h4>
                        <p class="card-text">Total Equipment</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-tools fa-2x"></i>
                    </div>
                </div>
                <a href="{{ route('equipment.index') }}" class="text-white text-decoration-none">
                    <small>View all equipment <i class="fas fa-arrow-right"></i></small>
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-3" style="margin-top: 20px;">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $participantsCount ?? 0 }}</h4>
                        <p class="card-text">Total Participants</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
                <a href="{{ route('participants.index') }}" class="text-white text-decoration-none">
                    <small>View all participants <i class="fas fa-arrow-right"></i></small>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3" style="margin-top: 20px;">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $projectsCount ?? 0 }}</h4>
                        <p class="card-text">Active Projects</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-project-diagram fa-2x"></i>
                    </div>
                </div>
                <a href="{{ route('projects.index') }}" class="text-white text-decoration-none">
                    <small>View all projects <i class="fas fa-arrow-right"></i></small>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3" style="margin-top: 20px;">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $programsCount ?? 0 }}</h4>
                        <p class="card-text">Active Programs</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-th-large fa-2x"></i>
                    </div>
                </div>
                <a href="{{ route('programs.index') }}" class="text-white text-decoration-none">
                    <small>View all programs <i class="fas fa-arrow-right"></i></small>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-uppercase text-muted mb-2">Facility Management</h6>
                        <div class="list-group list-group-flush">
                            <a href="{{ route('facilities.create') }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-plus me-2"></i>Register New Facility
                            </a>
                            <a href="{{ route('facilities.index') }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-list me-2"></i>View All Facilities
                            </a>
                            <a href="{{ route('services.create') }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-handshake-angle me-2"></i>Link Service to Facility
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-uppercase text-muted mb-2">Service & Equipment</h6>
                        <div class="list-group list-group-flush">
                            <a href="{{ route('services.create') }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-plus me-2"></i>Add New Service
                            </a>
                            <a href="{{ route('equipment.create') }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-plus me-2"></i>Register Equipment
                            </a>
                            <a href="{{ route('equipment.index') }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-list-check me-2"></i>Browse Equipment
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-clock me-2"></i>Recent Activity
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4 mb-3">
                        <h6 class="text-uppercase text-muted mb-2"><i class="fas fa-building me-2"></i>New Facilities</h6>
                        <ul class="list-group list-group-flush small">
                            @forelse($recentFacilities as $f)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $f->name }}</span>
                                <a href="{{ route('facilities.show', $f) }}" class="text-decoration-none"><i class="fas fa-arrow-right"></i></a>
                            </li>
                            @empty
                            <li class="list-group-item text-muted">No recent facilities</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <h6 class="text-uppercase text-muted mb-2"><i class="fas fa-cogs me-2"></i>New Services</h6>
                        <ul class="list-group list-group-flush small">
                            @forelse($recentServices as $s)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $s->name ?? 'Service' }}</span>
                                <a href="{{ route('services.show', $s) }}" class="text-decoration-none"><i class="fas fa-arrow-right"></i></a>
                            </li>
                            @empty
                            <li class="list-group-item text-muted">No recent services</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <h6 class="text-uppercase text-muted mb-2"><i class="fas fa-tools me-2"></i>New Equipment</h6>
                        <ul class="list-group list-group-flush small">
                            @forelse($recentEquipment as $e)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $e->name }}</span>
                                <a href="{{ route('equipment.show', $e) }}" class="text-decoration-none"><i class="fas fa-arrow-right"></i></a>
                            </li>
                            @empty
                            <li class="list-group-item text-muted">No recent equipment</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-lg-6 mb-3">
                        <h6 class="text-uppercase text-muted mb-2"><i class="fas fa-project-diagram me-2"></i>New Projects</h6>
                        <ul class="list-group list-group-flush small">
                            @forelse($recentProjects as $p)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $p->title }}</span>
                                <a href="{{ route('projects.show', $p) }}" class="text-decoration-none"><i class="fas fa-arrow-right"></i></a>
                            </li>
                            @empty
                            <li class="list-group-item text-muted">No recent projects</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <h6 class="text-uppercase text-muted mb-2"><i class="fas fa-trophy me-2"></i>New Outcomes</h6>
                        <ul class="list-group list-group-flush small">
                            @forelse($recentOutcomes as $o)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $o->title }}</span>
                                <a href="{{ route('outcomes.show', $o) }}" class="text-decoration-none"><i class="fas fa-arrow-right"></i></a>
                            </li>
                            @empty
                            <li class="list-group-item text-muted">No recent outcomes</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Add any dashboard-specific JavaScript here
    console.log('Dashboard loaded successfully');
</script>
@endpush