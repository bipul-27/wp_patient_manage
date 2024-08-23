<?php

namespace FluentPlugin\App\Models;

use FluentPlugin\App\Models\Model;

class PrescriptionTest extends Model
{
    protected $table = 'prescription_tests';

    protected $fillable = [
        'doctor_id',
        'patient_id',
        'prescription_id',
        'test_name',
        'extra_info'
    ];

    public function prescription()
    {
        return $this->belongsTo(Prescription::class, 'prescription_id');
    }
}
