<?php

namespace ps\LaraLogger\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityLog extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'table_id',
        'table',
        'model',
        'action',
        'description',
        'old_data',
        'new_data',
        'url',
        'method',
        'ip',
        'os',
        'browser',
        'device',
        'agent',
        'latitude',
        'longitude',
        'city',
        'region',
        'region_code',
        'region_name',
        'area_code',
        'dma_code',
        'country_code',
        'country_name',
        'continent_code',
        'continent_name',
        'timezone',
        'location_accuracy_radius',
        'geo_location_service_delay',
        'geo_location_service_status',
        'isp_name',
        'isp_organization',
        'isp_autonomous_system_number',
        'isp_service_status',
    ];
}