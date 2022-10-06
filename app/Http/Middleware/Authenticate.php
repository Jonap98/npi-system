<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            // $users = UsersModel::select(
            //     'name',
            //     'email'
            // )->get();
    
            // // $usuarios = User::latest()->get();
            // // return response([
            // //     'result' => true,
            // //     'data' => $users,
            // // ]);
            // // return view('auth.login')->with('usuarios', $usuarios);

            // return route('auth.login', array('usuarios' => $usuarios));
            return route('login');
        }
    }
}
