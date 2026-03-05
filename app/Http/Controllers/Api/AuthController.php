<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Carbon\Carbon;

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

    /**
     * Send password reset code to email
     */
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:user,username',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('username', $request->email)->first();

        // Generate 6-digit reset code
        $resetCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Delete any existing reset codes for this user
        PasswordReset::where('email', $request->email)->delete();

        // Save reset code to database
        PasswordReset::create([
            'email' => $request->email,
            'token' => $resetCode,
            'created_at' => Carbon::now(),
        ]);

        // Send email with reset code
        try {
            Mail::send('emails.password-reset', ['code' => $resetCode, 'user' => $user], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Password Reset Code');
            });

            return response()->json([
                'message' => 'Reset code sent to your email',
                'email' => $request->email,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to send reset code. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Verify reset code
     */
    public function verifyResetCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:user,username',
            'code' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $passwordReset = PasswordReset::where('email', $request->email)
            ->where('token', $request->code)
            ->first();

        if (!$passwordReset) {
            return response()->json([
                'message' => 'Invalid or expired reset code',
            ], 400);
        }

        // Check if code is expired (15 minutes)
        $expiryTime = Carbon::parse($passwordReset->created_at)->addMinutes(15);
        if (Carbon::now()->greaterThan($expiryTime)) {
            $passwordReset->delete();
            return response()->json([
                'message' => 'Reset code has expired. Please request a new one.',
            ], 400);
        }

        return response()->json([
            'message' => 'Code verified successfully',
            'verified' => true,
        ]);
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:user,username',
            'code' => 'required|string|size:6',
            'password' => 'required|string|min:4|confirmed',
            'password_confirmation' => 'required|string|min:4',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Verify code again
        $passwordReset = PasswordReset::where('email', $request->email)
            ->where('token', $request->code)
            ->first();

        if (!$passwordReset) {
            return response()->json([
                'message' => 'Invalid or expired reset code',
            ], 400);
        }

        // Check if code is expired (15 minutes)
        $expiryTime = Carbon::parse($passwordReset->created_at)->addMinutes(15);
        if (Carbon::now()->greaterThan($expiryTime)) {
            $passwordReset->delete();
            return response()->json([
                'message' => 'Reset code has expired. Please request a new one.',
            ], 400);
        }

        // Update password
        $user = User::where('username', $request->email)->first();
        $user->password = hash('sha256', $request->password);
        $user->save();

        // Delete used reset code
        $passwordReset->delete();

        // Optionally revoke all existing tokens
        // $user->tokens()->delete();

        return response()->json([
            'message' => 'Password reset successfully',
        ]);
    }

    /**
     * Resend reset code
     */
    public function resendResetCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,username',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('username', $request->email)->first();

        // Generate new 6-digit reset code
        $resetCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Delete old reset codes
        PasswordReset::where('email', $request->email)->delete();

        // Save new reset code
        PasswordReset::create([
            'email' => $request->email,
            'token' => $resetCode,
            'created_at' => Carbon::now(),
        ]);

        // Send email with new reset code
        try {
            Mail::send('emails.password-reset', ['code' => $resetCode, 'user' => $user], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('New Password Reset Code');
            });

            return response()->json([
                'message' => 'New reset code sent to your email',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to send reset code. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function index()
    {
        try {
            // Get all users, excluding password field
            $users = User::select('id', 'name', 'username', 'phone', 'usertype', 'cat', 'password')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Users retrieved successfully',
                'data' => $users
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve users',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
