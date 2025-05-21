<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Http\Requests\AuthUser\RegisterRequest;
use App\Http\Requests\AuthUser\LoginRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    // Register a new user
    public function register(RegisterRequest $request)
    {
        try {
            $registerDetails = $request->validated();
            $registerDetails['password'] = bcrypt($registerDetails['password']);
            User::create($registerDetails);
            return response()->apiSuccess('User registered successfully', array());
        } catch (\Exception $e) {
            Log::error('Registration Error: ' . $e->getMessage(), [
                'stack' => $e->getTraceAsString()
            ]);
            return response()->apiFail('Something went wrong');
        }
    }

    // Login and return token
    public function login(LoginRequest $request)
    {
        try {
            if (!Auth::attempt($request->validated())) {
                return response()->apiFail('Invalid login credentials', [], 401);
            }
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->apiSuccess('Login successful', [
                'user' => $user,
                'token' => 'Bearer '.$token
            ]);
        } catch (\Exception $e) {
            Log::error('Login Error: ' . $e->getMessage(), [
                'stack' => $e->getTraceAsString()
            ]);
            return response()->apiFail('Something went wrong');
        }
    }

    // Get authenticated user details
    public function user(Request $request)
    {
        $user = $request->user();
        return response()->apiSuccess('User details fetched successfully', $user);
    }

    // Logout and revoke token
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->apiSuccess('Logged out successfully');
    }
}
