<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemSetting;

class SystemSettingSeeder extends Seeder
{
    public function run(): void
    {
        SystemSetting::create([
            'company_name'         => 'Acme Business Solutions',
            'email'                => 'info@acme.com',
            'mobile_number'        => '+94771234567',
            'telephone_number'     => '+94112345678',
            'website'              => 'https://www.acme.com',
            'address_line_1'       => 'No. 123, Main Street',
            'address_line_2'       => 'Colombo 03',
            'address_line_3'       => null,
            'city'                 => 'Colombo',
            'district'             => 'Colombo',
            'province_state'       => 'Western',
            'postal_code'          => '00300',
            'country'              => 'Sri Lanka',
            'system_currency'      => 'LKR',
            'header_display_type'  => 'company_logo',
            'company_logo'         => null,
            'company_slogan'       => 'Your trusted business partner',
            'slogan_font_size'     => 12,
            'document_header_text' => 'Acme Business Solutions',
            'header_font_size'     => 24,
            'theme_color'          => '#4F46E5',
            'bank_details'         => "Bank Name: Bank of Ceylon\nAccount Name: Acme Business Solutions\nAccount Number: 1234567890\nBranch Code: 001",
            'is_active'            => true,
            'is_delete'            => false,
        ]);
    }
    
}