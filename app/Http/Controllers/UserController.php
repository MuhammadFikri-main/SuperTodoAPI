<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Exception;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Handle login request
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => "required|email|exists:users,email", // Make sure the correct DB name and column
            'password' => "required|min:6"
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 422); // Unprocessable Entity
        }
        try {
            $email = $request->email;
            $password = $request->password;

            $user = User::where('email', $email)->first();

            // If user doesn't exist or password is incorrect
            if (!$user || !Hash::check($password, $user->password)) {
                return response()->json([
                    'code' => 'UNAUTHORIZED',
                    'status' => 401,
                    'description' => 'Invalid',
                    'message' => 'Invalid email or password',
                ], 400); // Bad Request
            }

            // Generate a token (Sanctum token assumed here)
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'user' => $user,
                'message' => 'Login successfully',
                'token' => $token
            ], 200); // OK

        } catch (Exception $e) {
            return response()->json([
                'x-request-id' => $request->header('X-Request-Id'),
                'error-message' => $e->getMessage(),
            ], 500); // Internal Server Error
        }
    }

    /**
     * Handle logout request
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'code' => 'UNAUTHORIZED',
                    'description' => 'Invalid',
                    'message' => 'User not authenticated',
                ], 401); // Unauthorized
            }

            // Revoke all tokens associated with the user
            $user->tokens()->delete();

            // Optionally, revoke all tokens (if needed)
            // $user->tokens->each(function ($token) {
            //     $token->delete();
            // });

            return response()->json([
                'user' => $user,
                'message' => 'Logged out successfully'
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'x-request-id' => $request->header('X-Request-Id'),
                'error-message' => $e->getMessage(),
            ], 500); // Internal Server Error
        }
    }
}
