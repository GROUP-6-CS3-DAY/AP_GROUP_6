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
            'project_id' => ['required', 'string'],
            'title' => 'required|string|max:255',
            'outcome_type' => 'required|string|in:publication,patent,product,prototype,certification,other',
            'description' => 'required|string',
            'date_achieved' => 'required|date',
            'commercialization_status' => 'nullable|string|in:Ready,In Progress,Commercialized,Not Applicable',
            'quality_certification' => 'nullable|string|max:255',
            'impact' => 'nullable|string',
            'artifact_link' => 'nullable|url|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'project_id.required' => 'Please select a project.',
            'title.required' => 'Outcome title is required.',
            'outcome_type.required' => 'Please select an outcome type.',
            'description.required' => 'Outcome description is required.',
            'date_achieved.required' => 'Date achieved is required.',
            'artifact_link.url' => 'Artifact link must be a valid URL.',
        ];
    }
}
