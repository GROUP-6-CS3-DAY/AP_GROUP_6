<div class="mb-3">
    <label for="full_name">Full Name</label>
    <input type="text" name="full_name" value="{{ old('full_name', $participant->full_name ?? '') }}" class="form-control" required>
</div>

<div class="mb-3">
    <label for="email">Email</label>
    <input type="email" name="email" value="{{ old('email', $participant->email ?? '') }}" class="form-control" required>
</div>

<div class="mb-3">
    <label for="affiliation">Affiliation</label>
    <select name="affiliation" class="form-control" required>
        @foreach(['cs','se','engineering','other'] as $option)
            <option value="{{ $option }}" {{ old('affiliation', $participant->affiliation ?? '') == $option ? 'selected' : '' }}>
                {{ strtoupper($option) }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="specialization">Specialization</label>
    <select name="specialization" class="form-control" required>
        @foreach(['software','hardware','business'] as $option)
            <option value="{{ $option }}" {{ old('specialization', $participant->specialization ?? '') == $option ? 'selected' : '' }}>
                {{ ucfirst($option) }}
            </option>
        @endforeach
    </select>
</div>

<input type="hidden" name="cross_skill_trained" value="0">
<input type="checkbox" name="cross_skill_trained" value="1"
       {{ old('cross_skill_trained', $participant->cross_skill_trained ?? false) ? 'checked' : '' }}>
<label for="cross_skill_trained">Cross Skill Trained</label>

<div class="mb-3">
    <label for="institution">Institution</label>
    <select name="institution" class="form-control" required>
        @foreach(['scit','cedat','unipod','uiri','lwera'] as $option)
            <option value="{{ $option }}" {{ old('institution', $participant->institution ?? '') == $option ? 'selected' : '' }}>
                {{ strtoupper($option) }}
            </option>
        @endforeach
    </select>
</div>
