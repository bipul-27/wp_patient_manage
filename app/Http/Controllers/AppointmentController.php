<?php

namespace FluentPlugin\App\Http\Controllers;

use FluentPlugin\App\Models\Doctor;
use FluentPlugin\App\Models\Patient;
use FluentPlugin\App\Models\Appointment;
use FluentPlugin\Framework\Request\Request;

class AppointmentController extends Controller 
{

    public function index(Request $request, $doctorId)
{
    $filter_date = $request->get('filter_date');
    $search = sanitize_text_field($request->get('contact_number'));

    $appointments = Appointment::with('patient');

    if (!empty($search)) {
        $appointments = $appointments->whereHas('patient', function($query) use ($search) {
            $query->where('contact_info', 'LIKE', "%$search%");
        });
    }

    if (!empty($filter_date)) {
        $appointments = $appointments->where('date', $filter_date);
    }

    $appointments = $appointments->orderBy('date', 'asc')
                                 ->orderBy('start_time', 'asc')
                                 ->get();

    return [
        'appointments' => $appointments
    ];
}


    public function store(Request $request, $doctorId)
    {
        $appointment = $request->get('appointment');
        
        $data=[
            'patient_id' => $appointment['patient_id'],
            'doctor_id' => $doctorId,
            'date' => $appointment['date'],
            'start_time' => $appointment['start_time'],
            'end_time' => $appointment['end_time']
        ];

        Appointment::create($data);

        $allAppointment = Appointment::where('doctor_id',$doctorId)->get();

        return[
            'message' => 'success',
            'appointment' => $allAppointment
        ];
    }

    public function destroy($doctorId, $appointmentId)
    {
        
        $appointment = Appointment::where('id', $appointmentId)
                                  ->where('doctor_id', $doctorId)
                                  ->firstOrFail();

        $appointment->delete();

        return[
            'message' => __('Appointment Cancelled')
        ];

    }

    public function patientAppointment(Request $request, $doctorId, $patientId)
    {
        $appointment = Appointment::where('patient_id', $patientId)->where('doctor_id',$doctorId)->get();
        // $appointment = $appointment->get();
        return[
            'appointments' => $appointment
        ];
    }
}