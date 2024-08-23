<?php

namespace FluentPlugin\App\Models;

use FluentPlugin\App\Models\Model;

class Medicine extends Model 
{
    
    protected $table = 'medicine';
    protected $fillable = [
        'doctor_id',
        'name',
        'generic_name',
        'brand_name',
        'dosage_form',
        'strength'
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}