<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Equipment extends Model
{
    use HasFactory;

    protected $table = 'equipment';

    protected $fillable = [
        'facility_id',
        'name',
        'capabilities',
        'description',
        'inventory_code',
        'usage_domain',
        'support_phase'
    ];

    protected $casts = [
        'capabilities' => 'array',
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class, 'facility_id');
    }

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
            'biomedical' => 'Biomedical'
        ];
    }

    public static function getSupportPhaseOptions(): array
    {
        return [
            'training' => 'Training',
            'prototyping' => 'Prototyping',
            'testing' => 'Testing',
            'commercialization' => 'Commercialization',
            'research' => 'Research'
        ];
    }

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
            'renewable_energy_testing' => 'Renewable Energy Testing',
            'automation_systems' => 'Automation Systems'
        ];
    }
}
