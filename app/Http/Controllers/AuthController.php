<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'surname' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'password' => ['required', Password::min(8)->mixedCase()->numbers()],
                'phone' => 'required|string',
                'address' => 'required|string',
                'date_of_birth' => 'required|string',
                'collegiate_number' => 'required|string',
                'role_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $validData = $validator->validated();

            $newUser = User::create([
                'name' => $validData['name'],
                'surname' => $validData['surname'],
                'email' => $validData['email'],
                'password' => bcrypt($validData['password']),
                'phone' => $validData['phone'],
                'address' => $validData['address'],
                'date_of_birth' => $validData['date_of_birth'],
                'collegiate_number' => $validData['collegiate_number'],
                'role_id' => $validData['role_id']
            ]);

            $token = $newUser->createToken('apiToken')->plainTextToken;

            return response()->json([
                'message' => 'Users registered',
                'data' => $newUser,
                'token' => $token
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error('User cannot be registered' . $th->getMessage());

            return response()->json([
                'message' => 'Error registering user'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function login(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ], [
                'email' => 'Email or password are invalid',
                'password' => 'Email or password are invalid'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $validData = $validator->validated();

            $user = User::where('email', $validData['email'])->first();

            if (!$user) {
                return response()->json([
                    'message' => 'Email or password are invalid',
                ], Response::HTTP_FORBIDDEN);
            }

            $token = $user->createToken('apiToken')->plainTextToken;

            return response()->json([
                'message' => 'User logged in',
                'data' => $user,
                'token' => $token
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error('User cannot be logged in' . $th->getMessage());

            return response()->json([
                'message' => 'Error login in'
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

    public function logout(Request $request)
    {
        try {
            $headerToken = $request->bearerToken();

            $token = PersonalAccessToken::findToken($headerToken);

            $token->delete();

            return response()->json([
                'message' => 'User logged out',
            ], Response::HTTP_OK);
            
        } catch (\Throwable $th) {
            Log::error('Error logging user out' . $th->getMessage());

            return response()->json([
                'message' => 'Error logging user out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
