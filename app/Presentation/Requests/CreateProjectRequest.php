<?php

namespace App\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Domain\ValueObjects\InnovationFocus;
use App\Domain\ValueObjects\PrototypeStage;

class CreateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $innovationFocusOptions = array_keys(InnovationFocus::getAllOptions());
        $prototypeStageOptions = array_keys(PrototypeStage::getAllOptions());

        return [
            'program_id' => 'required|exists:programs,id',
            'facility_id' => 'required|exists:facilities,id',
            'title' => 'required|string|min:5|max:255',
            'nature_of_project' => 'required|string',
            'description' => 'required|string|min:20',
            'innovation_focus' => 'required|string|in:' . implode(',', $innovationFocusOptions),
            'prototype_stage' => 'required|string|in:' . implode(',', $prototypeStageOptions),
            'testing_requirements' => 'required|string',
            'commercialization_plan' => 'required|string',
        ];
    }
}
