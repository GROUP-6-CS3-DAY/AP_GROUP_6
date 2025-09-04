<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $primaryKey = 'project_ID';

    protected $fillable = [
        'project_ID',
        'program_ID',
        'facility_ID',
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
        return $this->belongsTo(Program::class, 'program_ID');
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class, 'facility_ID');
    }

}
