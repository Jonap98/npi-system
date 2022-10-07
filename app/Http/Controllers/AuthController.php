<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }
    
    public function login()
    {
        return response(['entry' => true]);
        $credentials = request(['username', 'password']);

        // if (! $token = auth()->attempt($credentials)) {
            return response()->json(['message' => 'Favor de revisar sus credenciales'], 401);
        // }

        return $this->respondWithToken('Inicio de sesión exitoso!', $token);
    }
    
    public function me()
    {
        return response()->json($this->guard()->user());
    }
    
    public function logout()
    {
        $this->guard()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
    
    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }
    
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }
    
    public function guard()
    {
        return Auth::guard();
    }
}