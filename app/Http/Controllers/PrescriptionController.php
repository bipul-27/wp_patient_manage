<?php

namespace FluentPlugin\App\Http\Controllers;

use FluentPlugin\App\Models\Doctor;
use FluentPlugin\App\Models\Patient;
use FluentPlugin\App\Models\Appointment;
use FluentPlugin\App\Models\Prescription;
use FluentPlugin\App\Models\PrescriptionDiagnosis;
use FluentPlugin\App\Models\PrescriptionMedicine;
use FluentPlugin\App\Models\PrescriptionTest;
use FluentPlugin\Framework\Request\Request;

class PrescriptionController extends Controller 
{
    public function store(Request $request, $doctorId, $patientId)
    {
        $advice = $request->get('advice');
        $diagnoses = $request->get('diagnosisList');   
        $medicines = $request->get('medicinesList');
        $tests = $request->get('testsList');
    
        // Create the prescription
        $prescription = Prescription::create([
            'doctor_id' => $doctorId,
            'patient_id' => $patientId,
            'advice' => $advice
        ]);
    
        // Store each diagnosis
        foreach ($diagnoses as $diagnosis) {
            PrescriptionDiagnosis::create([
                'prescription_id' => $prescription->id,
                'diagnosis' => $diagnosis['name']
            ]);
        }
    
        // Store each medicine
        foreach ($medicines as $medicine) {
            PrescriptionMedicine::create([
                'doctor_id' => $doctorId,
                'patient_id' => $patientId,
                'prescription_id' => $prescription->id,
                'medicine_name' => $medicine['name'],
                'medicine_type' => $medicine['type'],
                'dosage' => $medicine['dosage'],
                'duration' => $medicine['duration'],
                'morning' => $medicine['times']['morning'],
                'noon' => $medicine['times']['noon'],
                'night' => $medicine['times']['night']
            ]);
        }
    
        // Store each test
        foreach ($tests as $test) {
            PrescriptionTest::create([
                'doctor_id' => $doctorId,
                'patient_id' => $patientId,
                'prescription_id' => $prescription->id,
                'test_name' => $test['name'],
                'extra_info' => $test['info']
            ]);
        }
    
        // Retrieve the stored data to return
        $allDiagnosis = PrescriptionDiagnosis::where('prescription_id', $prescription->id)->get();
        $allMedicine = PrescriptionMedicine::where('prescription_id', $prescription->id)->get();
        $allTest = PrescriptionTest::where('prescription_id', $prescription->id)->get();
    
        return [
            'message' => 'success',
            'advice' => $prescription->advice,
            'diagnosisList' => $allDiagnosis,
            'medicinesList' => $allMedicine,
            'testsList' => $allTest
        ];
    }
    public function fetchPrescriptions(Request $request, $doctorId, $patientId)
    {
        // Fetch all prescriptions for the given doctor and patient
        $prescriptions = Prescription::where('doctor_id', $doctorId)
            ->where('patient_id', $patientId)
            ->with(['diagnoses', 'medicines', 'tests'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Structure the data for the response
        $response = $prescriptions->map(function($prescription) {
            return [
                'id' => $prescription->id,
                'advice' => $prescription->advice,
                'diagnoses' => $prescription->diagnoses->map(function($diagnosis) {
                    return [
                        'id' => $diagnosis->id,
                        'diagnosis' => $diagnosis->diagnosis,
                    ];
                }),
                'medicines' => $prescription->medicines->map(function($medicine) {
                    return [
                        'id' => $medicine->id,
                        'medicine_name' => $medicine->medicine_name,
                        'medicine_type' => $medicine->medicine_type,
                        'dosage' => $medicine->dosage,
                        'frequency' => $medicine->frequency,
                        'duration' => $medicine->duration,
                        'morning' => $medicine->morning,
                        'noon' => $medicine->noon,
                        'night' => $medicine->night,
                    ];
                }),
                'tests' => $prescription->tests->map(function($test) {
                    return [
                        'id' => $test->id,
                        'test_name' => $test->test_name,
                        'extra_info' => $test->extra_info,
                    ];
                }),
            ];
        });

        return[
            'prescriptions' => $response,
        ];
    }

    public function fetchDrugs(Request $request, $doctorId, $patientId)
    {
        $drugs = PrescriptionMedicine::where('doctor_id', $doctorId)
                      ->where('patient_id', $patientId)
                      ->get();
        return[
            'drugs' => $drugs
        ];
    }

    public function fetchTests(Request $request, $doctorId, $patientId)
    {
        $tests = PrescriptionTest::where('doctor_id', $doctorId)
                                    ->where('patient_id', $patientId)
                                    ->get();
        return[
            'tests' => $tests
        ];
    }
    
    
}