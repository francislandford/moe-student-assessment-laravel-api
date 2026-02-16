<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    // Signup / Register

    // Login
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('username', $request->email)->first();

        if (!$user || hash('sha256', $request->password) !== $user->password) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // 🔧 Check user status before proceeding
//        if ($user->status != 1) {
//            return response()->json(['message' => 'Your account is inactive. Contact administrator.'], 403);
//        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => new UserResource($user),
            'token' => $token,
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }

    // Get authenticated user
    public function user(Request $request)
    {
        return response()->json([
            'user' => new UserResource($request->user()),
        ]);
    }

}
