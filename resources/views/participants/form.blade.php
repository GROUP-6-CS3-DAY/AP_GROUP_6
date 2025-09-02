<div class="card shadow-sm p-4 mt-4" style="border: none; border-radius: 5px; padding-top: 40px; padding-left: 450px; padding-right: 40px;">
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="full_name" class="form-label fw-bold" style="color: #48284A;">Full Name</label>
            <input type="text" name="full_name" value="{{ old('full_name', $participant->full_name ?? '') }}" 
                   class="form-control" required style="border-radius: 3px; padding: 10px;border-width: 0.02cm;">
        </div>

        <div class="col-md-6 mb-3" style="padding-top: 20px;">
            <label for="email" class="form-label fw-bold" style="color: #48284A;">Email</label>
            <input type="email" name="email" value="{{ old('email', $participant->email ?? '') }}" 
                   class="form-control" required style="border-radius: 3px; padding: 10px; border-width: 0.02cm;">
        </div>

        <div class="col-md-6 mb-3"style="padding-top: 20px;">
            <label for="affiliation" class="form-label fw-bold" style="color: #48284A;">Affiliation</label>
            <select name="affiliation" class="form-select" required style="border-radius: 3px; padding: 10px;">
                @foreach(['cs','se','engineering','other'] as $option)
                    <option value="{{ $option }}" {{ old('affiliation', $participant->affiliation ?? '') == $option ? 'selected' : '' }}>
                        {{ strtoupper($option) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6 mb-3"style="padding-top: 20px;">
            <label for="specialization" class="form-label fw-bold" style="color: #48284A;">Specialization</label>
            <select name="specialization" class="form-select" required style="border-radius: 5px; padding: 10px;">
                @foreach(['software','hardware','business'] as $option)
                    <option value="{{ $option }}" {{ old('specialization', $participant->specialization ?? '') == $option ? 'selected' : '' }}>
                        {{ ucfirst($option) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6 mb-3"style="padding-top: 20px;">
            <label for="institution" class="form-label fw-bold" style="color: #48284A;">Institution</label>
            <select name="institution" class="form-select" required style="border-radius: 5px; padding: 10px;">
                @foreach(['scit','cedat','unipod','uiri','lwera'] as $option)
                    <option value="{{ $option }}" {{ old('institution', $participant->institution ?? '') == $option ? 'selected' : '' }}>
                        {{ strtoupper($option) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6 mb-3"style="padding-top: 20px;">
            <div class="form-check mt-4">
                <input type="hidden" name="cross_skill_trained" value="0">
                <input type="checkbox" name="cross_skill_trained" value="1" class="form-check-input"
                    {{ old('cross_skill_trained', $participant->cross_skill_trained ?? false) ? 'checked' : '' }}>
                <label class="form-check-label fw-bold" style="color: #48284A; border-width: 0.02cm;">Cross Skill Trained</label>
            </div>
        </div>
    </div>
</div>

<div class="mt-4" style="padding-left: 450px; padding-top: 20px;">
    <button type="submit" class="btn" style="background: #A1869E; color: white; padding: 10px 30px; border-radius: 5px; border-width: 0cm;">
        Save Participant
    </button>
</div>
