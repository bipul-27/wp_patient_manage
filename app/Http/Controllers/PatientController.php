<?php

namespace FluentPlugin\App\Http\Controllers;

use FluentPlugin\App\Models\Patient;
use FluentPlugin\Framework\Request\Request;


class PatientController extends Controller 
{
    public function index(Request $request, $doctorId)
{
    
    $healthCondition = sanitize_text_field($request->get('health_condition'));
    $contactInfo = sanitize_text_field($request->get('query'));

    
    $query = Patient::where('doctor_id', $doctorId);

    
    if (!empty($healthCondition)) {
        $query->where('health_condition', $healthCondition);
    }

    
    if (!empty($contactInfo)) {
        $query->where('contact_info', 'LIKE', "%$contactInfo%");
    }

    
    $patients = $query->get();

    
    return [
        'patients' => $patients
    ];
}


    public function show(Request $request, $doctorId, $patientId)
    {
        $patient = Patient::where('id', $patientId)
            ->where('doctor_id', $doctorId)
            ->first();
        return[
            'patient' => $patient
        ];
    }
    

    public function store(Request $request,$doctorId)
    {
        $patient = $request->get('patients');
        $rules=[
            'name'=>'required',
            'email'=>'required|email|unique:patients,email',
            'age' => 'required|integer|min:0',
            'gender'=>'required|unique:patients,contact_info',
            'contact_info' =>'required' ,
            'health_condition'=> 'required',
            'address' => 'required',
            'dob' => 'required',
            'weight' => 'required',
            'height' => 'required',
            'blood_group' => 'required',
            'blood_pressure' => 'required',
            'bmi' => 'required'

        ];
       
        $validation = $this->validate($patient, $rules);

        $data = [
        'doctor_id' => $doctorId,
        'name'=> sanitize_text_field($validation['name']),
        'email'=> sanitize_email($validation['email']), 
        'age'=> sanitize_text_field($validation['age']), 
        'gender'=> $validation['gender'], 
        'contact_info'=>sanitize_text_field($validation['contact_info']), 
        'health_condition'=> $validation['health_condition'],
        'address' => sanitize_text_field( $validation['address'] ),
        'occupation' => sanitize_text_field($validation['occupation']),
        'dob' => $validation['dob'],
        'weight' => $validation['weight'],
        'height' => $validation['height'],
        'blood_group' => sanitize_text_field( $validation['blood_group'] ),
        'blood_pressure' => sanitize_text_field( $validation['blood_pressure'] ),
        'bmi' => sanitize_text_field( $validation['bmi'] )
        ];

        Patient::create($data);

        $allPatients = Patient::where('doctor_id', $doctorId)->get();

        return[
            'message' => 'success',
            'patients' => $allPatients
        ];

        
    }

    public function update(Request $request, $doctorId, $patientId)
    {
        $rules=[
            'name'=>'required',
            'email'=>'required|email|unique:patients,email',
            'age' => 'required|integer|min:0',
            'gender'=>'required|unique:patients,contact_info',
            'contact_info' =>'required' ,
            'health_condition'=> 'required',
            'address' => 'required',
            'dob' => 'required',
            'weight' => 'required',
            'height' => 'required',
            'blood_group' => 'required',
            'blood_pressure' => 'required',
            'bmi' => 'required'
        ];
       
        $validation = $this->validate($request->all(), $rules);
        $data = [
            'doctor_id' => $doctorId,
        'name'=> sanitize_text_field($validation['name']),
        'email'=> sanitize_email($validation['email']), 
        'age'=> sanitize_text_field($validation['age']), 
        'gender'=> $validation['gender'], 
        'contact_info'=>sanitize_text_field($validation['contact_info']), 
        'health_condition'=> $validation['health_condition'],
        'address' => sanitize_text_field( $validation['address'] ),
        'occupation' => sanitize_text_field($validation['occupation']),
        'dob' => $validation['dob'],
        'weight' => $validation['weight'],
        'height' => $validation['height'],
        'blood_group' => sanitize_text_field( $validation['blood_group'] ),
        'blood_pressure' => sanitize_text_field( $validation['blood_pressure'] ),
        'bmi' => sanitize_text_field( $validation['bmi'] )
            ];


        $patient = Patient::where('id',$patientId)
                          ->where('doctor_id',$doctorId)
                          ->firstOrFail();
        $patient->update($data);

        return [
            'message' => __('Patient Updated Successfully')
        ];
    }

    public function destroy($doctorId,$id)
    {
        $patient = Patient::where('id',$id)
                          ->where('doctor_id',$doctorId)
                          ->firstOrFail();
        $patient->delete();
        return [
            'message' => __('Doctor Deleted Successfully')
        ];
    }
    public function getPatientDetails($doctorId, $patientId)
{
    $patient = Patient::findOrFail($patientId);
    $patientDetails = $patient->details()->latest()->first();
    $patientHistory = $patient->details()->orderBy('created_at', 'desc')->get();

    return response()->json([
        'patient' => $patient,
        'patientDetails' => $patientDetails,
        'patientHistory' => $patientHistory
    ]);
}

    public function savePatientDetails(Request $request, $doctorId, $patientId)
{
    $validatedData = $request->validate([
        'sickness' => 'required|string',
        'medicine' => 'required|string',
        'tests' => 'required|string',
        'follow_up_date' => 'required|date',
    ]);

    $patientDetail = new PatientDetail($validatedData);
    $patientDetail->patient_id = $patientId;
    $patientDetail->save();

    return response()->json(['message' => 'Patient details saved successfully']);
}



}