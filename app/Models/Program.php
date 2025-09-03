<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $primaryKey = 'program_ID';

    protected $fillable = [
        'program_ID',
        'name',
        'description',
        'national_alignment',
        'focus_areas',
        'phases'
    ];

    public function projects()
    {
        return $this->hasMany(Project::class, 'program_ID');
    }

    public function getRouteKeyName()
    {
        return 'program_ID';
    }
}
