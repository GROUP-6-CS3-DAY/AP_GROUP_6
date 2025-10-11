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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'project_id' => 'required|exists:projects,project_id',
            'outcome_type' => 'required|string|max:255',
            'quality_certification' => 'required|string|max:255',
            'date_achieved' => 'required|date',
            'commercialization_status' => 'required|string|max:255',
            'impact' => 'required|string|max:255',
            'artifact_link' => 'required|url',
        ];
    }
}
