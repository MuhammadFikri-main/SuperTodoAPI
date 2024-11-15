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

            $user = new User;
            // $user->token = Str::random(32) . time();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            if(!$user){
                return response()->json([
                    'message' => "Failed to create user.",
                    'description' => "registration failed"
                ]);
            }

            return response()->json([
                'message' => "User registered successfully",
                'user' => $user
            ], 201);


        } catch(Exception $e){
            return response()->json([
                'message' => 'Internal Server Error'
            ], 500);
        }
    }
}
