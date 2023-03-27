<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UsuariosController extends Controller
{
    public function index() {
        $usuarios = User::select(
            'id',
            'name',
            'username',
            'email',
            'role',
        )
        ->where('role', 'like', 'NPI%')
        ->get();

        return view('usuarios.index', array('usuarios' => $usuarios));
    }

    public function update(Request $request, $id) {

        $usuario = User::findOrFail($id);

        $usuario->name = $request->nombre;
        $usuario->username = $request->username;
        $usuario->email = $request->email;
        $usuario->role = $request->role;

        $usuario->save();

        return redirect()->route('usuarios')->with('success', 'Usuario actualizado existosamente');
    }

    public function destroy($id) {
        User::destroy($id);

        return redirect()->route('usuarios')->with('success', 'Usuario eliminado exitosamente');
    }
}
