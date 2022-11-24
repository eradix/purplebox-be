<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request) {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
         ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => "Register Successful.",
            'data' => $user,
            'access_token' => $token, 
            'token_type' => 'Bearer', 
        ], 201);
    }

    public function login(Request $request) {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $token =  $user->createToken('token')->plainTextToken; 
            return response()->json([
                'message' => "Login Successfull",
                'data' => $user,
                'access_token' => $token, 
                'token_type' => 'Bearer', 
            ], 200); 
        } 
        else{ 
            return response()->json(['message'=>'Invalid Credentials'], 401); 
        } 
    }
}
