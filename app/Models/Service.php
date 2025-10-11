<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'services';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'facility_id',
        'name',
        'description',
        'category',
        'skill_type',
    ];

    /**
     * Get the facility that offers this service.
     */
    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class, 'facility_id', 'id');
    }

    /**
     * Get the service category options.
     */
    public static function getCategoryOptions(): array
    {
        return [
            'machining' => 'Machining',
            'testing' => 'Testing',
            'training' => 'Training',
            'prototyping' => 'Prototyping',
            'fabrication' => 'Fabrication',
            'analysis' => 'Analysis',
            'consultation' => 'Consultation',
        ];
    }

    /**
     * Get the skill type options.
     */
    public static function getSkillTypeOptions(): array
    {
        return [
            'hardware' => 'Hardware',
            'software' => 'Software',
            'integration' => 'Integration',
            'business' => 'Business',
            'research' => 'Research',
        ];
    }

    /**
     * Scope to filter services by category.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope to filter services by skill type.
     */
    public function scopeBySkillType($query, $skillType)
    {
        return $query->where('skill_type', $skillType);
    }

    /**
     * Scope to filter services by facility.
     */
    public function scopeByFacility($query, $facilityId)
    {
        return $query->where('facility_id', $facilityId);
    }

    /**
     * Scope to search services by name or description.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        });
    }
}
