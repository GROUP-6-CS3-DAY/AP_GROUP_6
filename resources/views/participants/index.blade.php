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
            Participants
        </div>
    </div>
   <div class="row mb-4" style="padding-top: 40px;padding-bottom: 40px;">
    <div class="col-auto">
        <a href="{{ route('participants.create') }}" 
           class="btn" 
           style="background: #A1869E; 
                  font-family: 'Inria Serif', serif;; 
                  color: #fff; 
                  font-weight: 500; 
                  border-radius: 0.2rem; 
                  padding: 0.5rem 1.5rem; 
                  text-decoration: none;">
            Add Participant
        </a>
    </div>
</div>




    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered" style="border-spacing: 0 8px; border-collapse: separate;">
        <thead>
            <tr style="background-color: #f8f9fa;">
                <th style="padding: 16px 24px; border-top: 1px solid #dee2e6;">Name</th>
                <th style="padding: 16px 24px; border-top: 1px solid #dee2e6;">Email</th>
                <th style="padding: 16px 24px; border-top: 1px solid #dee2e6;">Affiliation</th>
                <th style="padding: 16px 24px; border-top: 1px solid #dee2e6;">Specialization</th>
                <th style="padding: 16px 24px; border-top: 1px solid #dee2e6;">Institution</th>
                <th style="padding: 16px 24px; border-top: 1px solid #dee2e6;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($participants as $participant)
                <tr style="background-color: white;">
                    <td style="padding: 16px 24px; vertical-align: middle;">{{ $participant->full_name }}</td>
                    <td style="padding: 16px 24px; vertical-align: middle;">{{ $participant->email }}</td>
                    <td style="padding: 16px 24px; vertical-align: middle;">{{ ucfirst($participant->affiliation) }}</td>
                    <td style="padding: 16px 24px; vertical-align: middle;">{{ ucfirst($participant->specialization) }}</td>
                    <td style="padding: 16px 24px; vertical-align: middle;">{{ strtoupper($participant->institution) }}</td>
                    <td style="padding: 16px 24px; vertical-align: middle;">
                        <div class="d-flex gap-2">
                            <a href="{{ route('participants.show', $participant->participant_id) }}" class="btn btn-info btn-sm">View</a>
                            <a href="{{ route('participants.edit', $participant->participant_id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('participants.destroy', $participant->participant_id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this participant?')">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
