<?php

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


Route::get('/users', [UserController::class, 'getAllUsers']);
Route::get('/patients', [UserController::class, 'getAllPatients']);
Route::get('/dentists', [UserController::class, 'getAllDentists']);
Route::get('/users/{id}', [UserController::class, 'getProfile']);
Route::post('/users', [UserController::class, 'createProfile']);


Route::get('/treatments', [TreatmentController::class, 'getAllTreatments']);
Route::post('/treatments', [TreatmentController::class, 'createTreatment']);