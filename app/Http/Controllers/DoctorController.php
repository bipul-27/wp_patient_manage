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
            $query = $query->where('speciality','LIKE', "%$search%");
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
            'name' => 'required',
            'contact_info' => 'required',
            'speciality' => 'required',
        ];
    
        
        $validation = $this->validate($doctor, $rules);         
            $data = [
                'username' => sanitize_text_field($validation['username']),
                'email' => sanitize_email($validation['email']),
                'name' => sanitize_text_field($validation['name']),
                'contact_info' => sanitize_text_field($validation['contact_info']),
                'speciality' => sanitize_text_field($validation['speciality']),
            ];
    
            
            Doctor::create($data);
            $allDoctors = Doctor::all();
    
            return [
                'message' => 'success',
                'doctors' => $allDoctors
            ];
    }
    


    public function update(Request $request,$id)
    {
        $rules = [
            'username' => 'required|string|unique:doctors,username,' . $id,
            'email' => 'required|string|email|max:255|unique:doctors,email,' . $id,
            'name' => 'required|string|max:255',
            'contact_info' => 'required|string|max:255',
            'speciality' => 'required|string|max:255',
        ];
        
        $validation = $this->validate($request->all(), $rules);
        $doctor=Doctor::findOrFail($id);
        $data = [
            'username' => sanitize_text_field($validation['username']),
            'email' => sanitize_email($validation['email']),
            'name' => sanitize_text_field($validation['name']),
            'contact_info' => sanitize_text_field($validation['contact_info']),
            'speciality' => sanitize_text_field($validation['speciality']),
        ];
        $doctor->update($data);
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
