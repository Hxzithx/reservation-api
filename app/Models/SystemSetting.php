<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
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
    ];

    protected $casts = [
        'slogan_font_size' => 'integer',
        'header_font_size' => 'integer',
        'data' => 'array',
    ];
}