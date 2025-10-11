<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParticipantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:participants,email',
            'affiliation' => 'required|in:cs,se,engineering,other',
            'specialization' => 'nullable|in:software,hardware,business',
            'institution' => 'required|in:scit,cedat,unipod,uiri,lwera',
            'cross_skill_trained' => 'boolean',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'full_name.required' => 'Participant.FullName is required.',
            'email.required' => 'Participant.Email is required.',
            'email.unique' => 'Participant.Email already exists.',
            'affiliation.required' => 'Participant.Affiliation is required.',
            'affiliation.in' => 'Invalid affiliation value.',
            'specialization.in' => 'Invalid specialization value.',
            'institution.required' => 'Institution is required.',
            'institution.in' => 'Invalid institution value.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Business Rule 3: CrossSkillTrained can only be true if Specialization is set
            if ($this->input('cross_skill_trained') == true && empty($this->input('specialization'))) {
                $validator->errors()->add('cross_skill_trained', 'Cross-skill flag requires Specialization.');
            }
        });
    }
}