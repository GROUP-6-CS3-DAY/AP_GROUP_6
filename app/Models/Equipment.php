<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $primaryKey = 'equipment_id';

    protected $fillable = [
        'facility_id',
        'name',
        'capabilities',
        'description',
        'inventory_code',
        'usage_domain',
        'support_phase',
    ];

    /**
     * Get the facility that owns this equipment.
     */
    public function facility()
    {
        return $this->belongsTo(Facility::class, 'facility_id', 'facility_id');
    }
}
