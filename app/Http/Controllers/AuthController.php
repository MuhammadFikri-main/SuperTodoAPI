<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Summary of register
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function register(Request $request){
        //validate register data
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed'
        ]);

        if($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()]);
        }

        try{
            //Check if email already exist
            $user = User::where('email', $request->email)->first();

            if($user){
                return response()->json([
                    'message' => "Email is already registered",
                    'status' => 409,
                    'code' => 'CONFLICT',
                    'description' => 'conflict'
                ]);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password), // Hashing the password
            ]);

            if(!$user){
                return response()->json([
                    'message' => "Failed to create user.",
                    'description' => "registration failed"
                ]);
            }

            $token = $user->createToken($request->name);

            return response()->json([
                'message' => "User registered successfully",
                'user' => $user,
                'token' => $token->plainTextToken
            ], 201); // Created


        } catch(Exception $e){
            return response()->json([
                'message' => 'Internal Server Error'
            ], 500); // Internal Server Error
        }
    }

    /**
     * Summary of login
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => "required|email|exists:users,email", // Make sure the correct DB name and column
            'password' => "required|confirmed"
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
     * Summary of logout
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function logout(Request $request){
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
