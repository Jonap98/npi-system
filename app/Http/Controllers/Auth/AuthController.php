<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\User;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function __construct() {
        // $this->middleware('auth:api', ['except' => ['login']]);
        // $this->middleware('jwt.verify');
    }
    public function index() {
        // dd(url());

        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        // return response([
        //     'user_name' => $request->user_name,
        //     'password' => $request->password,
        //     // 'token' => $token
        // ]);
        $credentials = $request->only('user_name', 'password');
        // $credentials = $request->only('name', 'plain_password');
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return back()->with('error', 'Favor de revisar sus credenciales');
                // return response()->json([
                //     'credentials' => $credentials,
                //     'error' => 'invalid_credentials'
                // ], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        // return response()->json(compact('token'));
        // return response([
        //     'user_name' => $request->user_name,
        //     'password' => $request->password,
        //     'token' => $token
        // ]);
        // dd(url()->previous());
        return view('home');
        // return $this;
    }

    public function getAuthenticatedUser()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                    return response()->json(['user_not_found'], 404);
            }
            } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                    return response()->json(['token_expired'], $e->getStatusCode());
            } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                    return response()->json(['token_invalid'], $e->getStatusCode());
            } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
                    return response()->json(['token_absent'], $e->getStatusCode());
            }
            return response()->json(compact('user'));
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'last_name' => $request->get('last_name'),
            'email' => $request->get('email'),
            'plain_password' => $request->get('password'),
            'password' => Hash::make($request->get('password')),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user','token'),201);
    }
}
