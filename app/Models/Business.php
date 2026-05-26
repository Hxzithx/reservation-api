<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Business extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'companycode',
        'user_id',
        'name',
        'other_business_name_1',
        'other_business_name_2',
        'other_business_name_3',
        'type',
        'phone',
        'telephone',
        'FaxNo',
        'address_line_1',
        'address_line_2',
        'address_line_3',
        'city',
        'description',
        'is_active',
        'created_user',
        'update_by',
        'logo',
        'currency',
        'timezone',
        'website',
        'contact_person',
        'tax_id_1',
        'tax_id_2',
        'tax_id_3',
        'tax_id_4',
        'tax_id_5',
        'tax_registration_number_1',
        'tax_registration_number_2',
        'tax_registration_number_3',
        'tax_registration_number_4',
        'tax_registration_number_5',
        'StartOfFiscalYear',
        'CostingMethod',
        'isVat',
        'tax_number',
        'is_delete',
    ];

    protected function casts(): array
    {
        return [
            'is_active'   => 'boolean',
            'isVat'       => 'boolean',
            'is_delete'   => 'boolean',
            'deleted_at'  => 'datetime',
        ];
    }

    // Owner of the business
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // User who created the record
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_user');
    }

    // User who last updated the record
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'update_by');
    }

    // Contact person
    public function contactPerson()
    {
        return $this->belongsTo(User::class, 'contact_person');
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function staff()
    {
        return $this->hasMany(Staff::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function resources()
    {
        return $this->hasMany(Resource::class);
    }
}