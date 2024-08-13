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


    public function show($doctorId, $patientId)
    {
        return Patient::where('id', $patientId)
            ->where('doctor_id', $doctorId)
            ->first();
    }
    

    public function store(Request $request,$doctorId)
    {
        $patient = $request->get('patients');
        $rules=[
            'name'=>'required',
            'email'=>'required|email|unique:patients,email',
            'age' => 'required|integer|min:0',
            'gender'=>'required',
            'contact_info' => [
        'required',
        'regex:/^\+?[0-9\-\(\)\s]*$/',
        'unique:patients,contact_info'
    ],
            'health_condition'=> 'required'
        ];
       
        $validation = $this->validate($patient, $rules);
        $data = [
        'doctor_id' => $doctorId,
        'name'=> sanitize_text_field($patient['name']),
        'email'=> sanitize_email($patient['email']), 
        'age'=> sanitize_text_field($patient['age']), 
        'gender'=> $patient['gender'], 
        'contact_info'=>sanitize_text_field($patient['contact_info']), 
        'health_condition'=> $patient['health_condition']
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
            'email'=>'required|email|unique:patients,email,'.$patientId,
            'age' => 'required|integer|min:0',
            'gender'=>'required',
            'contact_info' => 'required',
            'health_condition'=> 'required'
        ];
       
        $validation = $this->validate($request->all(), $rules);
        $data = [
            'doctor_id' => $doctorId,
            'name'=> sanitize_text_field($validation['name']),
            'email'=> sanitize_email($validation['email']), 
            'age'=> sanitize_text_field($validation['age']), 
            'gender'=> $validation['gender'], 
            'contact_info'=>sanitize_text_field($validation['contact_info']), 
            'health_condition'=> $validation['health_condition']
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

}
