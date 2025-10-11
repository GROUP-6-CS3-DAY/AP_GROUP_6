<?php

namespace App\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEquipmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'facility_id' => 'required|exists:facilities,id',
            'name' => 'required|string|max:255',
            'capabilities' => 'required|array|min:1',
            'capabilities.*' => 'string',
            'description' => 'required|string',
            'inventory_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('equipment')->ignore($this->route('equipment'))
            ],
            'usage_domain' => 'required|string|in:electronics,mechanical,iot,software,renewable_energy,automation,materials,biomedical',
            'support_phase' => 'required|string|in:training,prototyping,testing,commercialization,research',
        ];
    }

    public function messages(): array
    {
        return [
            'facility_id.required' => 'Please select a facility.',
            'facility_id.exists' => 'The selected facility does not exist.',
            'capabilities.required' => 'Please select at least one capability.',
            'capabilities.min' => 'Equipment must have at least one capability.',
            'inventory_code.unique' => 'This inventory code is already in use.',
            'usage_domain.in' => 'Please select a valid usage domain.',
            'support_phase.in' => 'Please select a valid support phase.',
        ];
    }
}
