<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Outcome extends Model
{
    use HasFactory;

    protected $primaryKey = 'outcome_ID';
    protected $table = 'outcomes';

    protected $fillable = [
        'outcome_ID',
        'project_ID',
        'title',
        'description',
        'artifact_link',
        'outcome_type',
        'quality_certification',
        'commercialization_status',
        'impact',
        'date_achieved'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_ID');
    }
}
