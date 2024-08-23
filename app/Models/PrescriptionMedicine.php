<?php

namespace FluentPlugin\App\Models;

use FluentPlugin\App\Models\Model;

class PrescriptionMedicine extends Model
{
    protected $table = 'prescription_medicine';

    protected $fillable = [
        'doctor_id',
        'patient_id',
        'prescription_id',
        'medicine_name',
        'medicine_type',
        'dosage',
        'frequency',
        'duration',
        'morning',
        'noon',
        'night'
    ];

    public function prescription()
    {
        return $this->belongsTo(Prescription::class, 'prescription_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
