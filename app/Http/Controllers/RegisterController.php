<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Exception;
use App\Models\User;

class RegisterController extends Controller
{
    /**
     * Register new user
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registration(Request $request){
        //validate register date
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

            return response()->json([
                'message' => "User registered successfully",
                'user' => $user
            ], 201); // Created


        } catch(Exception $e){
            return response()->json([
                'message' => 'Internal Server Error'
            ], 500); // Internal Server Error
        }
    }
}
