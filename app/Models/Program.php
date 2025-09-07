<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    // Add this line to specify the primary key column name
    protected $primaryKey = 'program_id';

    protected $fillable = [
        'name',
        'description',
        'national_alignment',
        'focus_areas',
        'phases'
    ];

    // Add this casts array - this is what was missing!
    protected $casts = [
        'focus_areas' => 'array',
        'phases' => 'array',
    ];

    public function projects()
    {
        return $this->hasMany(Project::class, 'program_id');
    }
}