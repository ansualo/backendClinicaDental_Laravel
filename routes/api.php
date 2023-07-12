<?php

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


Route::get('/users', [UserController::class, 'getAllUsers']);
Route::get('/patients', [UserController::class, 'getAllPatients']);
Route::get('/dentists', [UserController::class, 'getAllDentists']);
Route::get('/profile', [UserController::class, 'getProfile'])->middleware('auth:sanctum');
Route::put('/profile', [UserController::class, 'updateProfile']);
Route::delete('/profile', [UserController::class, 'deleteMyAccount'])->middleware('auth:sanctum');
Route::post('/profile/{id}', [UserController::class, 'restoreAccount']);


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout/{id}', [AuthController::class, 'logout']);


Route::get('/treatments', [TreatmentController::class, 'getAllTreatments']);
Route::post('/treatments', [TreatmentController::class, 'createTreatment']);
Route::put('/treatments', [TreatmentController::class, 'updateTreatment']);
Route::delete('/treatments/{id}', [TreatmentController::class, 'deleteTreatment']);