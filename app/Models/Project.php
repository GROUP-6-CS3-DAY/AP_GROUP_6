<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Participant;
use App\Models\Outcome;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'commercialization_plan'
    ];

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class, 'facility_id');
    }

    public function participants()
    {
        return $this->hasMany(Participant::class, 'project_id', 'project_id');
    }

    public function outcomes()
    {
        return $this->hasMany(Outcome::class, 'project_id', 'project_id');
    }


}
