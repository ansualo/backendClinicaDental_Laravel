<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function getAllUsers()
    {
        try {
            $users = User::get();

            return response()->json([
                'message' => 'Users retrieved',
                'data' => $users
            ], Response::HTTP_OK);

        } catch (\Throwable $th) {
            Log::error('Error getting users'. $th->getMessage());

            return response()->json([
                'message' => 'Error retrieving users',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAllPatients()
    {
        try {
            $users = User::where('role_id', 1)->get();

            return response()->json([
                'message' => 'Users retrieved',
                'data' => $users
            ], Response::HTTP_OK);

        } catch (\Throwable $th) {
            Log::error('Error getting users'. $th->getMessage());

            return response()->json([
                'message' => 'Error retrieving users',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAllDentists()
    {
        try {
            $users = User::where('role_id', 2)->get();

            return response()->json([
                'message' => 'Users retrieved',
                'data' => $users
            ], Response::HTTP_OK);
            
        } catch (\Throwable $th) {
            Log::error('Error getting users'. $th->getMessage());

            return response()->json([
                'message' => 'Error retrieving users',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getProfile($id)
    {
        try {
            $user = User::where('user_id', $id)->get();

            return response()->json([
                'message' => 'Tasks retrieved',
                'data' => $user
            ], Response::HTTP_OK);

        } catch (\Throwable $th) {
            Log::error('Error getting users' . $th->getMessage());

            return response()->json([
                'message' => 'Error retrieving users'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function createProfile(Request $request){
        try {
           
            $validator = Validator::make($request->all(),[
                'name'=> 'required|string',
                'surname'=> 'required|string',
                'email'=> 'required|string',
                'password'=> 'required|string',
                'phone'=> 'required|string',
                'address'=> 'required|string',
                'date_of_birth'=> 'required|string',
                'collegiate_number'=> 'required|string',
                'role_id'=> 'required',
            ]);
        
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $validData = $validator->validated();

            $user = USer::create([
                'name' => $validData['name'],
                'surname' => $validData['surname'],
                'email' => $validData['email'],
                'password' => $validData['password'],
                'phone' => $validData['phone'],
                'address' => $validData['address'],
                'date_of_birth' => $validData['date_of_birth'],
                'collegiate_number' => $validData['collegiate_number'],
                'role_id' => $validData['role_id']
            ]);

            return response()->json([
                'message' => 'User created succesfully',
                'data' => $user
            ], Response::HTTP_OK);

        } catch (\Throwable $th) {
            Log::error('User cannot be created' . $th->getMessage());

            return response()->json([
                'message' => 'Error creating user'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
