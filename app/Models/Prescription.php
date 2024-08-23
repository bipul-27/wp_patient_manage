<?php

namespace FluentPlugin\App\Models;

use FluentPlugin\App\Models\Model;

class Prescription extends Model
{
    protected $table = 'prescription';

    protected $fillable = [
        'doctor_id',
        'patient_id',
        'advice'
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function diagnoses()
    {
        return $this->hasMany(PrescriptionDiagnosis::class, 'prescription_id');
    }

    public function tests()
    {
        return $this->hasMany(PrescriptionTest::class, 'prescription_id');
    }

    public function medicines()
    {
        return $this->hasMany(PrescriptionMedicine::class, 'prescription_id');
    }
}
