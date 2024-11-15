<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Handle login request
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login (Request $request){

        $validator = Validator::make($request->all(),[
            'email' => "required|email|existed:user",
            'password' => "required|"
        ]);

        if($validator->fails()){
            return response()->json(['message' => $validator->errors()->first()]);
        }

        return response()->json(['message'=>'Invalid credentials'],400);

    }
}
