<?php

namespace App\Http\Controllers\externals\ekanban;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\externals\ekanban\UserModel;

class RegisterController extends Controller
{
    public function index() {
        return view('externals.ekanban.register');
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required | string',
            'username' => 'required|string|unique:gen_users',
            'password' => 'required|string'
        ]);

        $user = UserModel::create([
            'userGuid' => Str::uuid(),
            'name' => $request->name,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'roles' => '[{"application":"REQUERIMIENTOS","role":[{"name":"Surtidor e-kanban","code":"AA13"}]}]',
        ]);

        if($user) {
            return back()->with('success', 'Usuario creado exitosamente!');
        }
    }
}
