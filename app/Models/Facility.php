<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Facility extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'facilities';

    // Using default 'id' primary key

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'location',
        'description',
        'partner_organization',
        'facility_type',
        'capabilities',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'capabilities' => 'array',
    ];

    /**
     * Get the services offered by this facility.
     */
    public function services(): HasMany
    {
        return $this->hasMany(Service::class, 'facility_id');
    }

    /**
     * Get the equipment available at this facility.
     */
    public function equipment(): HasMany
    {
        return $this->hasMany(Equipment::class, 'facility_id');
    }

    /**
     * Get the projects hosted at this facility.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'facility_id');
    }

    /**
     * Get the facility type options.
     */
    public static function getFacilityTypeOptions(): array
    {
        return [
            'workshop' => 'Workshop',
            'testing_center' => 'Testing Center',
            'laboratory' => 'Laboratory',
            'maker_space' => 'Maker Space',
            'innovation_hub' => 'Innovation Hub',
            'research_center' => 'Research Center',
        ];
    }

    /**
     * Get the capability options.
     */
    public static function getCapabilityOptions(): array
    {
        return [
            'cnc_machining' => 'CNC Machining',
            'pcb_fabrication' => 'PCB Fabrication',
            'materials_testing' => 'Materials Testing',
            '3d_printing' => '3D Printing',
            'welding' => 'Welding',
            'electronics_testing' => 'Electronics Testing',
            'software_development' => 'Software Development',
            'iot_prototyping' => 'IoT Prototyping',
            'renewable_energy' => 'Renewable Energy',
            'automation' => 'Automation',
        ];
    }

    /**
     * Scope to filter facilities by type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('facility_type', $type);
    }

    /**
     * Scope to filter facilities by partner organization.
     */
    public function scopeByPartner($query, $partner)
    {
        return $query->where('partner_organization', $partner);
    }

    /**
     * Scope to search facilities by name or description.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('location', 'like', "%{$search}%");
        });
    }
}