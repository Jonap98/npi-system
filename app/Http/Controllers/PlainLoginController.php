<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use App\Models\PartesModel;
use App\Models\UbicacionesModel;
use Illuminate\Support\Facades\Auth;
// use Symfony\Component\HttpFoundation\Cookie;

class PlainLoginController extends Controller
{
    public function login(Request $request, $route) {
        // return response(['data' => $request->password]);

        $pass = User::select(
            'user_name',
            'password as pass',
        )
        ->where('user_name', $request->user_name)
        ->get();

        foreach ($pass as $pwd) {
            // return response(['data' => $pwd]);

            if($request->password == $pwd->pass) {
                // return response(['ok' => $pwd]);
                // Usuario encontrado
                // return $pwd;
                $user = $pwd;
                $info = Auth::login($user);

                $this->setCookie();
                
                // $minutes = 60;
                // $response = new Response('Set Cookie');
                // $response->withCookie(cookie('loginResponse', 'qwerty', $minutes));
                // return $response;

                $partes = PartesModel::get();
        
                $ubicaciones = UbicacionesModel::get();

                return true;

                // return view('partes.create', array('partes' => $partes, 'ubicaciones' => $ubicaciones));
                return view('partes.create', array('partes' => $partes, 'ubicaciones' => $ubicaciones));

                // return view('partes.create');
            }
        }
        return false;
        // return response(['data' => $pass]);

    }

    public function setCookie() {
        $minutes = 60;
        $response = new Response('Set Cookie');
        $response->withCookie(cookie('loginResponse', 'qwerty', $minutes));
        // return $response;
    }
}
