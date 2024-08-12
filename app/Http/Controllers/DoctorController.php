<?php

namespace FluentPlugin\App\Http\Controllers;

use FluentPlugin\App\Models\Doctor;

use FluentPlugin\Framework\Request\Request;

class DoctorController extends Controller 
{
    public function index(Request $request)
    {
        $search = sanitize_text_field( $request->get('search') );
        $query = Doctor::query();

        if (!empty($search)) {
            $query = $query->where('speciality', $search);
        }
        $query = $query->get();

        return [
            'doctors' => $query
        ];
    }

    public function show($id)
    {
        return Doctor::find($id);
    }

    
    public function store(Request $request)
    {
        
        $doctor = $request->get('doctors');
    
        
        $rules = [
            'username' => 'required|string|unique:doctors,username',
            'email' => 'required|string|email|max:255|unique:doctors,email',
            'name' => 'required|string|max:255',
            'contact_info' => 'required|string|max:255',
            'speciality' => 'required|string|max:255',
        ];
        $messages = [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email address is already taken.',
            'speciality.required' => 'The speciality field is required.',
            'contact_info.required' => 'The contact info field is required.',
        ];
    
        
        $validation = $this->validate($doctor, $rules,$messages);
        
    
        try {
            
            $data = [
                'username' => sanitize_text_field($doctor['username']),
                'email' => sanitize_email($doctor['email']),
                'name' => sanitize_text_field($doctor['name']),
                'contact_info' => sanitize_text_field($doctor['contact_info']),
                'speciality' => sanitize_text_field($doctor['speciality']),
            ];
    
            
            Doctor::create($data);
            $allDoctors = Doctor::all();
    
            return [
                'message' => 'success',
                'doctors' => $allDoctors
            ];
    
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    


    public function update(Request $request,$id)
    {
        $doctor=Doctor::findOrFail($id);
        $doctor->update($request->all());
        return [
            'message' => __('Doctor Updated Successfully')
        ];
       
    }

    public function destroy($id)
    {
        
            $doctor = Doctor::findOrFail($id);
            $doctor->delete();

            return [
                'message' => __('Doctor Deleted Successfully')
            ];
    }

    
    

}
