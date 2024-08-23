<?php


use FluentPlugin\App\Http\Controllers\DoctorController;
use FluentPlugin\App\Http\Controllers\PatientController;
use FluentPlugin\App\Http\Controllers\PatientDetailsController;
use FluentPlugin\App\Http\Controllers\MedicineController;
use FluentPlugin\App\Http\Controllers\AppointmentController;
use FluentPlugin\App\Http\Controllers\PrescriptionController;

    $router->prefix('doctors')->group(function ($router) {
        $router->get('/', [DoctorController::class, 'index']);
        $router->post('/', [DoctorController::class, 'store']);
        $router->get('/{id}', [DoctorController::class, 'show'])->int('id');
        $router->put('/{id}', [DoctorController::class, 'update'])->int('id');
        $router->delete('/{id}', [DoctorController::class, 'destroy'])->int('id');
    });

    //Define routes for patients
    $router->prefix('doctors/{doctorId}/patients')->group(function ($router) {
        $router->get('/', [PatientController::class, 'index']);
        $router->post('/', [PatientController::class, 'store']);
        $router->get('/{id}', [PatientController::class, 'show'])->int('id');
        $router->put('/{id}', [PatientController::class, 'update'])->int('id');
        $router->delete('/{id}', [PatientController::class, 'destroy'])->int('id');
        $router->get('/{id}/appointments', [AppointmentController::class, 'patientAppointment'])->int('id');
        $router->post('/{id}/prescription', [PrescriptionController::class, 'store'])->int('id');
        $router->get('/{id}/prescription', [PrescriptionController::class, 'fetchPrescriptions'])->int('id');
        $router->get('/{id}/drugs', [PrescriptionController::class, 'fetchDrugs'])->int('id');
        $router->get('/{id}/tests', [PrescriptionController::class, 'fetchTests'])->int('id');
    });

    //Define routes for add medicine
    $router->prefix('doctors/{doctorId}/medicine')->group(function ($router){
        $router->get('/', [MedicineController::class, 'index']);
        $router->post('/',[MedicineController::class, 'store']);
    });

    $router->prefix('doctors/{doctorId}/appointments')->group(function($router){
        $router->get('/', [AppointmentController::class, 'index']);
        $router->post('/',[AppointmentController::class, 'store']);
        $router->delete('/{id}', [AppointmentController::class, 'destroy'])->int('id');
        
    });

    


