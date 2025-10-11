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
        'cross_skill_trained' => 'boolean'
    ];

    /**
     * Get the projects this participant is involved in.
     */
    public function project()
{
return $this->belongsTo(Project::class, 'project_id', 'project_id');
}

}
