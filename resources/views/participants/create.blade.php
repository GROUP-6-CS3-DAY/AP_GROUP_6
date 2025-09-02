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
            Add Participant
        </div>
    </div>
    <form action="{{ route('participants.store') }}" method="POST">
        @csrf
        @include('participants.form')
    </form>
</div>
@endsection
