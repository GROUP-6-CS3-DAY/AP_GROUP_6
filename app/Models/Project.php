<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Participant;
use App\Models\Outcome;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;
    
    protected $table = 'projects';
    protected $primaryKey = 'id';
    public $incrementing = true; // Change to true if using integer IDs
    protected $keyType = 'int';  // Change to 'int' if using integer IDs

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
        'status',
        'technical_requirements'
    ];

    protected $casts = [
        'technical_requirements' => 'array'
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class, 'program_id', 'id');
    }

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class, 'facility_id', 'id');
    }

    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class, 'project_id', 'id');
    }

    public function outcomes()
    {
        return $this->hasMany(Outcome::class, 'project_id', 'id');
    }


}
