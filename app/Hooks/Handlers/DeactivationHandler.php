<?php

namespace FluentPlugin\App\Hooks\Handlers;

use FluentPlugin\Framework\Foundation\Application;
use FluentPlugin\Database\Migrations\DoctorMigrator;
use FluentPlugin\Database\Migrations\PatientsMigrator;
use FluentPlugin\Database\Migrations\MedicineMigrator;
use FluentPlugin\Database\Migrations\AppointmentsMigrator;
use FluentPlugin\Database\Migrations\PrescriptionDiagnosesMigrator;
use FluentPlugin\Database\Migrations\PrescriptionTestsMigrator;
use FluentPlugin\Database\Migrations\PrescriptionMedicinesMigrator;
use FluentPlugin\Database\Migrations\PrescriptionMigrator;




class DeactivationHandler
{
    protected $app = null;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }
    
    public function handle()
    {
        PrescriptionDiagnosesMigrator::drop();
        PrescriptionTestsMigrator::drop();
        PrescriptionMedicinesMigrator::drop();
        PrescriptionMigrator::drop();
        AppointmentsMigrator::drop();
        MedicineMigrator::drop();
        PatientsMigrator::drop();
        DoctorMigrator::drop();
       
        
        
        
    }
}
