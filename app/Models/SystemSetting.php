<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'data',
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

    // ─── Relationships ────────────────────────────────────────────────

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_user');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}