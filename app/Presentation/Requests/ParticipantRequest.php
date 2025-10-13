<?php

namespace App\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ParticipantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('participants')->ignore($this->route('participant'))
            ],
            'affiliation' => 'required|string|in:cs,ee,me,ce,other',
            'institution' => 'required|string|in:scit,other',
            'specialization' => 'nullable|string|max:255',
            'cross_skill_trained' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => 'Full name is required.',
            'email.required' => 'Email address is required.',
            'email.unique' => 'This email address is already registered.',
            'affiliation.required' => 'Affiliation is required.',
            'affiliation.in' => 'Please select a valid affiliation.',
            'institution.required' => 'Institution is required.',
            'institution.in' => 'Please select a valid institution.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Business rule: Cross-skill flag requires Specialization
            if ($this->input('cross_skill_trained') && empty($this->input('specialization'))) {
                $validator->errors()->add('cross_skill_trained', 'Cross-skill flag requires Specialization.');
            }
        });
    }
}
