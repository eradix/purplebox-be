<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register() {
        return response()->json([
            "message" => "Login"
        ]);
    }

    public function login() {
        return response()->json([
            "message" => "Register"
        ]);
    }
}
