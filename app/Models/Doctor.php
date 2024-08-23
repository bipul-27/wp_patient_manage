<?php

namespace FluentPlugin\App\Models;

use FluentPlugin\App\Models\Model;

class Doctor extends Model
{
   
    protected $table = 'doctors';

    protected $fillable = [
        'username',
          'email', 
          'name',
           'speciality',
            'contact_info'
            
    ];

   

    public function patients()
    {
        return $this->hasMany(Patient::class);
    }
}
