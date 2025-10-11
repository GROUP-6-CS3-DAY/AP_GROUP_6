<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    // Using default 'id' primary key to match migration

    protected $fillable = [
        'name',
        'description',
        'national_alignment',
        'focus_areas',
        'phases'
    ];

    // Remove casts since migration uses string fields, not JSON
    // protected $casts = [
    //     'focus_areas' => 'array',
    //     'phases' => 'array',
    // ];

    public function projects()
    {
        return $this->hasMany(Project::class, 'program_id', 'id');
    }
}