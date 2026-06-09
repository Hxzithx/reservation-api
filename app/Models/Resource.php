<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resource extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'resource_type',
        'resource_name',
        'short_code',
        'capacity',
        'description',
        'photos',
        'job_title',
        'qualification',
        'years_experience',
        'languages',
        'gender',
        'bio',
        'display_order',
        'data',
        'is_active',
        'is_delete',
        'created_user',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'photos'           => 'array',
            'languages'        => 'array',
            'data'             => 'array',
            'is_active'        => 'boolean',
            'is_delete'        => 'boolean',
            'deleted_at'       => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function ($resource) {
            $resource->data = self::buildData($resource);
        });

        static::updating(function ($resource) {
            $resource->data = self::buildData($resource);
        });
    }

    private static function buildData($resource): array
    {
        $common = [
            'company_id'   => $resource->company_id,
            'resource_type' => $resource->resource_type,
            'resource_name' => $resource->resource_name,
            'short_code'    => $resource->short_code,
            'capacity'      => $resource->capacity,
            'description'   => $resource->description,
            'photos'        => $resource->photos,
            'display_order' => $resource->display_order,
            'is_active'     => $resource->is_active,
            'is_delete'     => $resource->is_delete,
            'created_user'  => $resource->created_user,
            'updated_by'    => $resource->updated_by,
        ];

        $typeSpecific = match ($resource->resource_type) {
            'person' => [
                'job_title'        => $resource->job_title,
                'qualification'    => $resource->qualification,
                'years_experience' => $resource->years_experience,
                'languages'        => $resource->languages,
                'gender'           => $resource->gender,
                'bio'              => $resource->bio,
            ],
            'room' => [
                'room_type'     => $resource->room_type,
                'floor'         => $resource->floor,
                'area'          => $resource->area,
                'pricing_model' => $resource->pricing_model,
                'min_stay'      => $resource->min_stay,
                'amenities'     => $resource->amenities,
            ],
            'equipment' => [
                'equipment_category' => $resource->equipment_category,
                'brand_model'        => $resource->brand_model,
                'serial_number'      => $resource->serial_number,
                'condition'          => $resource->condition,
                'hire_rate'          => $resource->hire_rate,
                'deposit_required'   => $resource->deposit_required,
            ],
            'timeslot' => [
                'slot_category' => $resource->slot_category,
                'virtual_type'  => $resource->virtual_type,
                'video_link'    => $resource->video_link,
                'max_group_size' => $resource->max_group_size,
            ],
            default => [],
        };

        return array_merge($common, $typeSpecific);
    }
}
