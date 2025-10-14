<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Participant extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'full_name',
        'email',
        'affiliation',
        'specialization',
        'institution',
        'cross_skill_trained',
        'project_id'
    ];

    protected $casts = [
        'cross_skill_trained' => 'boolean'
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
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }
}
