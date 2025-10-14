@extends('layouts.app')

@section('title', $outcome->getTitle() . ' - InnoTrack')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">{{ $outcome->getTitle() }}</h1>
            <div>
                <a href="{{ route('outcomes.edit', $outcome->getId()) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-1"></i>Edit
                </a>
                <a href="{{ route('outcomes.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Outcome Details</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Type:</strong>
                        <span class="badge bg-primary ms-2">{{ ucfirst($outcome->getOutcomeType()->getValue()) }}</span>
                    </div>
                    <div class="col-md-6">
                        <strong>Date Achieved:</strong>
                        <span class="ms-2">
                            @php
                                $dateAchieved = $outcome->getDateAchieved();
                                $formattedDate = $dateAchieved instanceof \Carbon\Carbon ? $dateAchieved->format('F d, Y') : \Carbon\Carbon::parse($dateAchieved)->format('F d, Y');
                            @endphp
                            {{ $formattedDate }}
                        </span>
                    </div>
                </div>
                
                @if($outcome->getQualityCertification())
                <div class="mb-3">
                    <strong>Quality Certification:</strong>
                    <p>{{ $outcome->getQualityCertification() }}</p>
                </div>
                @endif
                
                <div class="mb-3">
                    <strong>Description:</strong>
                    <p>{{ $outcome->getDescription() }}</p>
                </div>
                
                @if($outcome->getImpact())
                <div class="mb-3">
                    <strong>Impact:</strong>
                    <p>{{ $outcome->getImpact() }}</p>
                </div>
                @endif
                
                @if($outcome->getCommercializationStatus()->getValue())
                <div class="mb-3">
                    <strong>Commercialization Status:</strong>
                    <p>{{ $outcome->getCommercializationStatus()->getValue() }}</p>
                </div>
                @endif
                
                @if($outcome->getArtifactLink())
                <div class="mb-0">
                    <strong>Artifact Link:</strong>
                    <p>
                        <a href="{{ $outcome->getArtifactLink() }}" target="_blank" class="text-decoration-none">
                            {{ $outcome->getArtifactLink() }}
                            <i class="fas fa-external-link-alt ms-1"></i>
                        </a>
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Status & Metrics</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>Commercial Potential:</span>
                    <span class="badge bg-{{ $outcome->canBeCommercializaed() ? 'success' : 'secondary' }}">
                        {{ $outcome->canBeCommercializaed() ? 'Yes' : 'No' }}
                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>High Impact:</span>
                    <span class="badge bg-{{ $outcome->isHighImpact() ? 'warning' : 'secondary' }}">
                        {{ $outcome->isHighImpact() ? 'Yes' : 'No' }}
                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span>Days Since Achievement:</span>
                    <span class="badge bg-info">
                        @php
                            $dateAchieved = $outcome->getDateAchieved();
                            $dateAchievedCarbon = $dateAchieved instanceof \Carbon\Carbon ? $dateAchieved : \Carbon\Carbon::parse($dateAchieved);
                            $daysSince = abs($dateAchievedCarbon->diffInDays(now()));
                        @endphp
                        {{ $daysSince }} days
                    </span>
                </div>
            </div>
        </div>
        
        @if($outcome->getArtifactLink())
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body text-center">
                <a href="{{ $outcome->getArtifactLink() }}" target="_blank" class="btn btn-outline-primary">
                    <i class="fas fa-external-link-alt me-1"></i>View Artifact
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection