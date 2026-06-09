<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Override;

class SystemSetting extends Model
{
    use SoftDeletes;

    protected $table = 'system_setting';

    protected $fillable = [
        'company_name',
        'email',
        'mobile_number',
        'telephone_number',
        'website',
        'address_line_1',
        'address_line_2',
        'address_line_3',
        'city',
        'district',
        'province_state',
        'postal_code',
        'country',
        'system_currency',
        'header_display_type',
        'company_logo',
        'company_slogan',
        'slogan_font_size',
        'document_header_text',
        'header_font_size',
        'theme_color',
        'bank_details',
        'is_active',
        'is_delete',
        'created_user',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'data'       => 'array',
            'is_active'  => 'boolean',
            'is_delete'  => 'boolean',
            'deleted_at' => 'datetime',
        ];
    }

    #[Override]
   protected static function booted(): void
{
    static::creating(function ($systemSetting) {
        $systemSetting->data = self::buildData($systemSetting);
    });

    static::updating(function ($systemSetting) { 
        $systemSetting->data = self::buildData($systemSetting);
    });
}

    private static function buildData($systemSetting): array
    {
        return [
            'company_name' => $systemSetting->company_name,
            'email' => $systemSetting->email,
            'mobile_number' => $systemSetting->mobile_number,
            'telephone_number' => $systemSetting->telephone_number,
            'website' => $systemSetting->website,
            'address_line_1' => $systemSetting->address_line_1,
            'address_line_2' => $systemSetting->address_line_2,
            'address_line_3' => $systemSetting->address_line_3,
            'city' => $systemSetting->city,
            'district' => $systemSetting->district,
            'province_state' => $systemSetting->province_state,
            'postal_code' => $systemSetting->postal_code,
            'country' => $systemSetting->country,
            'system_currency' => $systemSetting->system_currency,
            'header_display_type' => $systemSetting->header_display_type,
            'company_logo' => $systemSetting->company_logo,
            'company_slogan' => $systemSetting->company_slogan,
            'slogan_font_size' => $systemSetting->slogan_font_size,
            'document_header_text' => $systemSetting->document_header_text,
            'header_font_size' => $systemSetting->header_font_size,
            'theme_color' => $systemSetting->theme_color,
            'bank_details' => $systemSetting->bank_details,
            'is_active' => $systemSetting->is_active,
            'is_delete' => $systemSetting->is_delete,
            'created_user' => $systemSetting->created_user,
            'updated_by' => $systemSetting->updated_by,
        ];
    }

    //Relationships 

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_user');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
