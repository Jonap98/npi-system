<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UbicacionesModel;

class UbicacionesController extends Controller
{
    public function index() {
        $ubicaciones = UbicacionesModel::select(
            'id',
            'ubicacion',
            'tipo'
        )->get();

        return view('ubicaciones.index', ['ubicaciones' => $ubicaciones]);
    }

    public function store(Request $request) {
        $validatedData = $request->validate([
            'ubicacion' => 'required|max:100'
        ]);

        $ubicacion = new UbicacionesModel();

        $ubicacion->ubicacion = $request->ubicacion;
        $ubicacion->tipo = $request->tipo;

        $ubicacion->save();

        return back()->with('success', 'Ubicación registrada exitosamente');
    }


    public function destroy($id) {
        UbicacionesModel::destroy($id);

        return back()->with('success', 'Ubicación eliminada exitosamente');
    }
}
