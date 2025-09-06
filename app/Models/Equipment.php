<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Equipment extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'equipment';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'capabilities' => 'array',
    ];

    /**
     * Get the facility that owns this equipment.
     */
    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    /**
     * Get the usage domain options.
     */
    public static function getUsageDomainOptions(): array
    {
        return [
            'electronics' => 'Electronics',
            'mechanical' => 'Mechanical',
            'iot' => 'IoT',
            'software' => 'Software',
            'renewable_energy' => 'Renewable Energy',
            'automation' => 'Automation',
            'materials' => 'Materials',
            'biomedical' => 'Biomedical',
        ];
    }

    /**
     * Get the support phase options.
     */
    public static function getSupportPhaseOptions(): array
    {
        return [
            'training' => 'Training',
            'prototyping' => 'Prototyping',
            'testing' => 'Testing',
            'commercialization' => 'Commercialization',
            'research' => 'Research',
        ];
    }

    /**
     * Scope to filter equipment by usage domain.
     */
    public function scopeByUsageDomain($query, $domain)
    {
        return $query->where('usage_domain', $domain);
    }

    /**
     * Scope to filter equipment by support phase.
     */
    public function scopeBySupportPhase($query, $phase)
    {
        return $query->where('support_phase', $phase);
    }

    /**
     * Scope to filter equipment by facility.
     */
    public function scopeByFacility($query, $facilityId)
    {
        return $query->where('facility_id', $facilityId);
    }

    /**
     * Scope to search equipment by name, description, or inventory code.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('inventory_code', 'like', "%{$search}%");
        });
    }

    /**
     * Scope to filter equipment by capability.
     */
    public function scopeByCapability($query, $capability)
    {
        return $query->whereJsonContains('capabilities', $capability);
    }
}
