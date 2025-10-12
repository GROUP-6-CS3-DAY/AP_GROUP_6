<?php

namespace App\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'program_id' => 'required|exists:programs,id',
            'facility_id' => 'required|exists:facilities,id',
            'title' => 'required|string|min:5|max:255',
            'nature_of_project' => 'required|string',
            'description' => 'required|string|min:20',
            'innovation_focus' => 'required|string|in:product,renewable_energy,iot,software,materials,automation',
            'prototype_stage' => 'required|string|in:concept,design,prototype,testing,production',
            'testing_requirements' => 'required|string',
            'commercialization_plan' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'program_id.required' => 'Please select a program.',
            'facility_id.required' => 'Please select a facility.',
            'title.min' => 'Project title must be at least 5 characters long.',
            'description.min' => 'Project description must be at least 20 characters long.',
        ];
    }
}
