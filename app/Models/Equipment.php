<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Facility;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Equipment extends Model
{
    use HasFactory;

    protected $primaryKey = 'equipment_ID';

    protected $table = 'equipments';

    protected $fillable = [
        'facility_ID',
        'name',
        'capabilities',
        'description',
        'inventory_code',
        'usage_domain',
        'support_phase'
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class, 'facility_ID');
    }
}
