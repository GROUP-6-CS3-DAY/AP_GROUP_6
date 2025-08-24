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
        'cross_skill_trained',
        'institution',
    ];

    protected $casts = [
        'cross_skill_trained' => 'boolean',
    ];

    /**
     * Get the projects this participant is involved in.
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_participants', 'participant_id', 'project_id')
            ->withPivot('role_on_project', 'skill_role')
            ->withTimestamps();
    }
}
