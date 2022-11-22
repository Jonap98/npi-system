<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class RegistroController extends Controller
{
    public function index() {
        return view('auth.register');
    }

    public function store(Request $request) {
        

        $validatedData = $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'role' =>  $request->admin ? 'NPI-admin' : 'NPI',
            'password' => Hash::make($request->password),
            'active' => 'Y'
        ]);

        return redirect('registro')->with('success', 'El usuario fue creado exitosamente');
    }
}
