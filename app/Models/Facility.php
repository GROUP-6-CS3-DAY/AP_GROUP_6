<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;

    protected $primaryKey = 'facility_id';

    protected $fillable = [
        'name',
        'location',
        'description',
        'partner_organization',
        'facility_type',
        'capabilities',
    ];

    protected $casts = [
        'capabilities' => 'array',
    ];

    /**
     * Get the services for this facility.
     */
    public function services()
    {
        return $this->hasMany(Service::class, 'facility_id', 'facility_id');
    }

    /**
     * Get the equipment for this facility.
     */
    public function equipment()
    {
        return $this->hasMany(Equipment::class, 'facility_id', 'facility_id');
    }

    /**
     * Get the projects for this facility.
     */
    public function projects()
    {
        return $this->hasMany(Project::class, 'facility_id', 'facility_id');
    }
}
