<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $primaryKey = 'service_id';

    protected $fillable = [
        'facility_id',
        'name',
        'description',
        'category',
        'skill_type',
    ];

    /**
     * Get the facility that owns this service.
     */
    public function facility()
    {
        return $this->belongsTo(Facility::class, 'facility_id', 'facility_id');
    }
}
