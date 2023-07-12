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

    // public function getProfile($id)
    // {
    //     try {
    //         $user = User::where('user_id', $id)->get();

    //         return response()->json([
    //             'message' => 'Tasks retrieved',
    //             'data' => $user
    //         ], Response::HTTP_OK);

    //     } catch (\Throwable $th) {
    //         Log::error('Error getting users' . $th->getMessage());

    //         return response()->json([
    //             'message' => 'Error retrieving users'
    //         ], Response::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }

}
