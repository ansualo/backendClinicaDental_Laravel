<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::get('/users', [UserController::class, 'getAllUsers'])->middleware('auth:sanctum', 'isAdmin');
Route::get('/patients', [UserController::class, 'getAllPatients'])->middleware('auth:sanctum', 'isAdmin');
Route::get('/dentists', [UserController::class, 'getAllDentists']);
Route::get('/profile', [UserController::class, 'getProfile'])->middleware('auth:sanctum');
Route::put('/profile', [UserController::class, 'updateProfile'])->middleware('auth:sanctum');
Route::delete('/profile', [UserController::class, 'deleteMyAccount'])->middleware('auth:sanctum');
Route::post('/profile/{id}', [UserController::class, 'restoreAccount'])->middleware('auth:sanctum', 'isAdmin');


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout/{id}', [AuthController::class, 'logout']);


Route::get('/treatments', [TreatmentController::class, 'getAllTreatments']);
Route::post('/treatments', [TreatmentController::class, 'createTreatment'])->middleware('auth:sanctum', 'isAdmin');
Route::put('/treatments', [TreatmentController::class, 'updateTreatment'])->middleware('auth:sanctum', 'isAdmin');
Route::delete('/treatments/{id}', [TreatmentController::class, 'deleteTreatment'])->middleware('auth:sanctum', 'isAdmin');


Route::get('/appointments', [AppointmentController::class, 'getAllAppointments'])->middleware('auth:sanctum', 'isAdmin');
Route::get('/appointments/{id}', [AppointmentController::class, 'getOneAppointment'])->middleware('auth:sanctum');
Route::get('/appointments/patient', [AppointmentController::class, 'getPatientAppointments'])->middleware('auth:sanctum');
Route::get('/appointments/doctor', [AppointmentController::class, 'getDoctorAppointments'])->middleware('auth:sanctum');
Route::post('/appointments', [AppointmentController::class, 'createAppointment'])->middleware('auth:sanctum');
Route::put('/appointments/{id}', [AppointmentController::class, 'updateAppointment'])->middleware('auth:sanctum');
Route::delete('/appointments/{id}', [AppointmentController::class, 'deleteAppointment'])->middleware('auth:sanctum');