<?php

namespace FluentPlugin\App\Models;

use FluentPlugin\App\Models\Model;

class PrescriptionDiagnosis extends Model
{
    protected $table = 'prescription_diagnoses';

    protected $fillable = [
        'prescription_id',
        'diagnosis'
    ];

    public function prescription()
    {
        return $this->belongsTo(Prescription::class, 'prescription_id');
    }
}
