<?php

namespace FluentPlugin\App\Http\Controllers;

use FluentPlugin\App\Models\Patient;
use FluentPlugin\Framework\Request\Request;


class PatientController extends Controller 
{
    public function index(Request $request, $doctorId)
    {
        $search = sanitize_text_field($request->get('health_condition'));
        $query = Patient::where('doctor_id', $doctorId);


        if (!empty($search))
        {
            $query = $query->where('health_condition',$search);
        }


        $patients = $query->get();


        return[
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
            'name'=>'required|string',
            'email'=>'required|string|email|max:255|unique:patients,email',
            'age'=>'required',
            'gender'=>'required',
            'contact_info' => 'required|string|max:255',
            'health_condition'=> 'required'
        ];
        $messages = [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email address is already taken.',
            'contact_info.required' => 'The contact info field is required.',
        ];
        $validation = $this->validate($patient, $rules,$messages);
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
            'name'=>'required|string',
            'email'=>'required|string|email|max:255|unique:patients,email,'.$patientId,
            'age'=>'required',
            'gender'=>'required',
            'contact_info' => 'required|string|max:255',
            'health_condition'=> 'required'
        ];
        $messages = [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email address is already taken.',
            'contact_info.required' => 'The contact info field is required.',
        ];
        $validation = $this->validate($request->all(), $rules,$messages);
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
