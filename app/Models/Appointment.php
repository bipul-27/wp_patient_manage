<?php

namespace FluentPlugin\App\Models;

use FluentPlugin\App\Models\Model;

class Appointment extends Model 
{

    protected $table = 'appointment';
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'date',
        'start_time',
        'end_time'
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}

