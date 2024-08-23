<?php

namespace FluentPlugin\App\Http\Controllers;

use FluentPlugin\App\Models\Doctor;
use FluentPlugin\App\Models\Medicine;
use FluentPlugin\Framework\Request\Request;


class MedicineController extends Controller 
{

    public function index(Request $request, $doctorId)
    {
        $searchMedicine = sanitize_text_field($request->get('search'));

        $query = Medicine::where('doctor_id',$doctorId);

        if(!empty($searchMedicine))
        {
            $query->where('name','LIKE', "%$searchMedicine%");
        }

        $medicines = $query->get();

        return[
            'medicines' => $medicines
        ];
    }


    public function store(Request $request, $doctorId)
    {
        $medicine = $request->get('medicine');

        $rules = [
            'name' => 'required',
            'generic_name' => 'required',
            'dosage_form' => 'required',
            'strength' => 'required'
        ];

        $messages = [
            'name.required' => 'The name field is required.',
            'generic_name.required' => 'The generic_name field is required.',
            'dosage_form.required' => 'The dosage form field is required.',
            'strength.required' => 'The strength info field is required.',
        ];

        $validation = $this->validate($medicine, $rules,$messages);

        $data=[
            'doctor_id' => $doctorId,
            'name' => sanitize_text_field($validation['name']),
            'generic_name' => sanitize_text_field($validation['generic_name']),
            'brand_name' => sanitize_text_field($validation['brand_name']),
            'dosage_form' => sanitize_text_field($validation['dosage_form']),
            'strength' => sanitize_text_field($validation['strength'])
        ];

        Medicine::create($data);
        
        $allMedicine = Medicine::where('doctor_id',$doctorId)->get();

        return[
            'message' => 'success',
            'medicine' => $allMedicine
        ];
    }
}