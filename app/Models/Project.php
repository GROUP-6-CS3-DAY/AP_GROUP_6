<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $primaryKey = 'project_id';

    protected $fillable = [
        'program_id',
        'facility_id',
        'title',
        'nature_of_project',
        'description',
        'innovation_focus',
        'prototype_stage',
        'testing_requirements',
        'commercialization_plan',
    ];

    /**
     * Get the program this project belongs to.
     */
    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id', 'program_id');
    }

    /**
     * Get the facility this project is hosted at.
     */
    public function facility()
    {
        return $this->belongsTo(Facility::class, 'facility_id', 'facility_id');
    }

    /**
     * Get the participants for this project.
     */
    public function participants()
    {
        return $this->belongsToMany(Participant::class, 'project_participants', 'project_id', 'participant_id')
            ->withPivot('role_on_project', 'skill_role')
            ->withTimestamps();
    }

    /**
     * Get the outcomes for this project.
     */
    public function outcomes()
    {
        return $this->hasMany(Outcome::class, 'project_id', 'project_id');
    }
}
