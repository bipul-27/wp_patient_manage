<?php

namespace FluentPlugin\Database;

use FluentPlugin\Database\Migrations\DoctorMigrator;
use FluentPlugin\Database\Migrations\PatientsMigrator;
use FluentPlugin\Database\Migrations\MedicineMigrator;
use FluentPlugin\Database\Migrations\AppointmentsMigrator;
use FluentPlugin\Database\Migrations\PrescriptionMigrator;
use FluentPlugin\Database\Migrations\PrescriptionDiagnosesMigrator;
use FluentPlugin\Database\Migrations\PrescriptionTestsMigrator;
use FluentPlugin\Database\Migrations\PrescriptionMedicinesMigrator;


class DBMigrator
{
    public static function run($network_wide = false)
    {
        static::migrate();
        
    }
    private static function migrate()
    {

        
        DoctorMigrator::migrate();
        PatientsMigrator::migrate();
        MedicineMigrator::migrate();
        AppointmentsMigrator::migrate();
        PrescriptionMigrator::migrate();
        PrescriptionDiagnosesMigrator::migrate();
        PrescriptionTestsMigrator::migrate();
        PrescriptionMedicinesMigrator::migrate();
        
    }
}
