<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $primaryKey = 'program_id';

    protected $fillable = [
        'name',
        'description',
        'national_alignment',
        'focus_areas',
        'phases',
    ];

    protected $casts = [
        'focus_areas' => 'array',
        'phases' => 'array',
    ];

    /**
     * Get the projects for this program.
     */
    public function projects()
    {
        return $this->hasMany(Project::class, 'program_id', 'program_id');
    }
}
