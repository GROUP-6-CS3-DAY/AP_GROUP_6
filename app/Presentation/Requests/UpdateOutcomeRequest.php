<?php

namespace App\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOutcomeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_id' => 'required|exists:projects,project_id',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'outcome_type' => 'required|string|in:publication,patent,product,prototype,certification,other',
            'quality_certification' => 'nullable|string|max:255',
            'impact' => 'nullable|string|max:1000',
            'date_achieved' => 'required|date|before_or_equal:today',
            'commercialization_status' => 'nullable|string|in:Ready,In Progress,Commercialized,Not Applicable',
            'artifact_link' => 'nullable|url|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'project_id.required' => 'Please select a project.',
            'project_id.exists' => 'The selected project does not exist.',
            'date_achieved.before_or_equal' => 'Date achieved cannot be in the future.',
            'artifact_link.url' => 'Please provide a valid URL for the artifact link.',
        ];
    }
}
