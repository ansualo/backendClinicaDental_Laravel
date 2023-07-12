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
            Log::error('Error getting users' . $th->getMessage());

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
            Log::error('Error getting users' . $th->getMessage());

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
            Log::error('Error getting users' . $th->getMessage());

            return response()->json([
                'message' => 'Error retrieving users',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getProfile()
    {
        try {

            $user = auth()->user();

            return response()->json([
                'message' => 'User found',
                'data' => $user
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error('Error retrieving user' . $th->getMessage());

            return response()->json([
                'message' => 'Error retrieving user'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateProfile(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'email' => 'email|unique:users,email',
                'phone' => 'string',
                'address' => 'string',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $validData = $validator->validated();

            $user = User::find($validData['id']);

            if (!$user) {
                return response()->json([
                    'message' => 'User cannot be found'
                ], Response::HTTP_OK);
            }

            if (isset($validData['email'])) {
                $user->email = $validData['email'];
            }

            if (isset($validData['phone'])) {
                $user->phone = $validData['phone'];
            }

            if (isset($validData['address'])) {
                $user->address = $validData['address'];
            }

            $user->save();

            return response()->json([
                'message' => 'User updated successfully',
                'data' => $user,
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error('Error updating user' . $th->getMessage());

            return response()->json([
                'message' => 'Error updating user'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteMyAccount()
    {
        try {
            $user = auth()->user();

            User::destroy($user->id);

            return response()->json([
                'message' => 'User deleted successfully',
            ], Response::HTTP_OK);

        } catch (\Throwable $th) {
            Log::error('Error deleting user' . $th->getMessage());

            return response()->json([
                'message' => 'Error deleting user'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function restoreAccount($id)
    {
        try {

            User::withTrashed()->where('id', $id)->restore();

            return response()->json([
                'message' => 'User restored',
            ], Response::HTTP_OK);

        } catch (\Throwable $th) {
            Log::error('Error restoring user' . $th->getMessage());

            return response()->json([
                'message' => 'Error restoring user'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
