<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResourceService extends Model
{
    protected $table = 'resource_services';

    protected $fillable = [
        'resource_id',
        'service_id',
    ];

    // ─── Relationships ────────────────────────────────────────────────

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}