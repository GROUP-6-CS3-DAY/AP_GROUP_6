<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Outcome extends Model
{
    use HasFactory;
    
    protected $table = 'outcomes';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'project_id',
        'title',
        'outcome_type',
        'description',
        'date_achieved',
        'commercialization_status',
        'quality_certification',
        'impact',
        'artifact_link'
    ];

    protected $casts = [
        'date_achieved' => 'datetime'  // Ensure Carbon object
    ];

    /**
     * Get the project this outcome belongs to.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }
}
