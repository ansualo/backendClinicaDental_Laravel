<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AppointmentController extends Controller
{
    public function getAllAppointments()
    {
        try {
            $appointments = Appointment::with([
                'patient:id,name,surname',
                'doctor:id,name,surname',
                'treatment:id,name,price'
            ])->get();

            return response()->json([
                'message' => 'Appointments retrieved',
                'data' => $appointments
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error('Error retrieving appointments' . $th->getMessage());

            return response()->json([
                'message' => 'Error retrieving appointments',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getOneAppointment($id)
    {
        try {

            $appointment = Appointment::where('id', $id)
            ->with([
                'patient:id,name,surname',
                'doctor:id,name,surname',
                'treatment:id,name,price'
            ])->get();

            return response()->json([
                'message' => 'Appointments retrieved',
                'data' => $appointment
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error('Error retrieving appointment' . $th->getMessage());

            return response()->json([
                'message' => 'Error retrieving appointment',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getPatientAppointments()
    {
        try {

            $patientId = auth()->user()->id;

            $appointments = Appointment::where('patient_id', $patientId)
            ->with([
                'patient:id,name,surname',
                'doctor:id,name,surname',
                'treatment:id,name,price'
            ])->get();

            return response()->json([
                'message' => 'Appointments retrieved',
                'data' => $appointments
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error('Error retrieving appointments' . $th->getMessage());

            return response()->json([
                'message' => 'Error retrieving appointments',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getDoctorAppointments()
    {
        try {

            $doctorId = auth()->user()->id;

            $appointments = Appointment::where('doctor_id', $doctorId)
            ->with([
                'patient:id,name,surname',
                'doctor:id,name,surname',
                'treatment:id,name,price'
            ])->get();

            return response()->json([
                'message' => 'Appointments retrieved',
                'data' => $appointments
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error('Error retrieving appointments' . $th->getMessage());

            return response()->json([
                'message' => 'Error retrieving appointments',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createAppointment(Request $request)
    {
        try {

            $userId = auth()->user()->id;

            $validator = Validator::make($request->all(), [
                'doctor_id' => 'required|integer',
                'treatment_id' => 'required|integer',
                'date' => 'required|date'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            };

            $validData = $validator->validated();

            //comprobamos que el doctor es correcto
            $doctorId = $validData['doctor_id'];
            $doctorRole = User::where('id', $doctorId)->first(['role_id'])->role_id;

            if ($doctorRole !== 2) {
                return response()->json([
                    'message' => 'Incorrect doctor'
                ], Response::HTTP_OK);
            }

            $appointment = Appointment::create([
                'patient_id' => $userId,
                'doctor_id' => $validData['doctor_id'],
                'treatment_id' => $validData['treatment_id'],
                'date' => $validData['date'],
                'created_at' => date_create(),
                'updated_at' => date_create()
            ]);

            return response()->json([
                'message' => 'Appointment created successfully',
                'data' => $appointment
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error('Error creating appointment' . $th->getMessage());

            return response()->json([
                'message' => 'Error creating appointment',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateAppointment(Request $request, $id)
    {
        try {

            $validator = Validator::make($request->all(), [
                'doctor_id' => 'integer',
                'treatment_id' => 'integer',
                'date' => 'date'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            };

            $validData = $validator->validated();

            //buscamos el appointment
            $appointment = Appointment::find($id, 'id');

            if (!$appointment) {
                return response()->json([
                    'message' => 'Appointment cannot be found'
                ], Response::HTTP_OK);
            }

            //comprobamos que la cita es del usuario
            $userTokenId = auth()->user()->id;
            $userId = Appointment::where('id', $id)->first(['patient_id'])->patient_id;

            if ($userTokenId !== $userId) {
                return response()->json([
                    'message' => 'Not your appointment'
                ], Response::HTTP_OK);
            }

            //comprobamos que el doctor es correcto
            $doctorId = $validData['doctor_id'];
            $doctorRole = User::where('id', $doctorId)->first(['role_id'])->role_id;

            if ($doctorRole !== 2) {
                return response()->json([
                    'message' => 'Incorrect doctor'
                ], Response::HTTP_OK);
            }

            //hacemos los cambios edl appointment
            if (isset($validData['doctor_id'])) {
                $appointment->doctor_id = $validData['doctor_id'];
            }

            if (isset($validData['treatment_id'])) {
                $appointment->treatment_id = $validData['treatment_id'];
            }

            if (isset($validData['date'])) {
                $appointment->date = $validData['date'];
            }

            $appointment->save();

            return response()->json([
                'message' => 'Appointment updated successfully',
                'data' => $appointment
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error('Error updating appointment' . $th->getMessage());

            return response()->json([
                'message' => 'Error updating appointment',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteAppointment($id)
    {
        try {

            //comprobamos que la cita es del usuario
            $userTokenId = auth()->user()->id;
            $userId = Appointment::where('id', $id)->first(['patient_id'])->patient_id;

            if ($userTokenId !== $userId) {
                return response()->json([
                    'message' => 'Not your appointment'
                ], Response::HTTP_OK);
            }

            Appointment::destroy($id);

            return response()->json([
                'message' => 'Appointment deleted succesfully',
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error('Error deleting appointment' . $th->getMessage());

            return response()->json([
                'message' => 'Error deleting appointment'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
