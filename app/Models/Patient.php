<?php

namespace FluentPlugin\App\Models;

use FluentPlugin\App\Models\Model;

class Patient extends Model 
{
    protected $table = 'patients';
    
    protected $fillable = [
        'doctor_id',
        'name',
        'email', 
        'age', 
        'gender', 
        'contact_info', 
        'health_condition',
        'address',
        'occupation',
        'dob',
        'weight',
        'height',
        'blood_group',
        'blood_pressure',
        'bmi',
        'profile_image'
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function details()
    {
        return $this->hasMany(PatientDetail::class);
    }
}
