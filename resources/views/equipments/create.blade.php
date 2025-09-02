@extends('layouts.app')

@section('title', 'Create Equipment')

@section('content')
    <h1>Create New Equipment</h1>

    <form action="{{ route('equipments.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
            <label for="facility_ID" class="form-label">Facility</label>
            <select class="form-select" id="facility_ID" name="facility_ID" required>
                <option value="">Select a facility</option>
                @foreach($facilities as $facility)
                    <option value="{{ $facility->facility_ID }}" {{ old('facility_ID') == $facility->facility_ID ? 'selected' : '' }}>{{ $facility->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="capabilities" class="form-label">Capabilities</label>
            <input type="text" class="form-control" id="capabilities" name="capabilities" value="{{ old('capabilities') }}">
            <div class="form-text">Comma-separated capabilities (optional)</div>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description">{{ old('description') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="inventory_code" class="form-label">Inventory Code</label>
            <input type="text" class="form-control" id="inventory_code" name="inventory_code" value="{{ old('inventory_code') }}" required>
        </div>

        <div class="mb-3">
            <label for="usage_domain" class="form-label">Usage Domain</label>
            <input type="text" class="form-control" id="usage_domain" name="usage_domain" value="{{ old('usage_domain') }}" required>
        </div>

        <div class="mb-3">
            <label for="support_phase" class="form-label">Support Phase</label>
            <input type="text" class="form-control" id="support_phase" name="support_phase" value="{{ old('support_phase') }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Create Equipment</button>
        <a href="{{ route('equipments.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection