@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Participant</h2>

    <form action="{{ route('participants.store') }}" method="POST">
        @csrf
        @include('participants.form')
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
@endsection
