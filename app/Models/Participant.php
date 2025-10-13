<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    protected $primaryKey = 'participant_id';

    protected $fillable = [
        'full_name',
        'email',
        'affiliation',
        'specialization',
        'institution',
        'cross_skill_trained'
    ];

    public static $rules = [
        'full_name' => 'required',
        'email' => 'required|unique:participants,email',
        'affiliation' => 'required',
        'specialization' => 'required_if:cross_skill_trained,true|nullable',
        'cross_skill_trained' => 'boolean'
    ];

    protected $casts = [
        'cross_skill_trained' => 'boolean',
    ];

    // Define the valid options for dropdowns
    public static function getAffiliationOptions(): array
    {
        return [
            'cs' => 'Computer Science',
            'ee' => 'Electrical Engineering',
            'me' => 'Mechanical Engineering',
            'ce' => 'Civil Engineering',
            'other' => 'Other'
        ];
    }

    public static function getInstitutionOptions(): array
    {
        return [
            'scit' => 'SCIT',
            'other' => 'Other'
        ];
    }

    public static function getSpecializationOptions(): array
    {
        return [
            'software' => 'Software Development',
            'hardware' => 'Hardware Design',
            'research' => 'Research & Development',
            'testing' => 'Testing & Quality Assurance',
            'project_management' => 'Project Management'
        ];
    }

    /**
     * Get the projects this participant is involved in.
     */
    public function project()
{
return $this->belongsTo(Project::class, 'project_id', 'project_id');
}

}
