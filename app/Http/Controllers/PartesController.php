<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PartesModel;
use App\Models\UbicacionesModel;
use App\Http\Controllers\PlainLoginController;

use Illuminate\Support\Facades\Auth;

class PartesController extends Controller
{
    public function index() {
        $partes = PartesModel::select(
            'id',
            'numero_de_parte',
            'descripcion',
            'um',
            'proyecto',
        )
        ->where('active', 1)
        ->orderBy('id', 'desc')
        ->get();

        $ubicaciones = UbicacionesModel::select(
            'ubicacion',
        )
        ->get();

        return view('partes.partes', array('partes' => $partes, 'ubicaciones' => $ubicaciones));
    }

    public function create() {

        return view('partes.create');
    }

    public function store(Request $request) {
        $partes = new PartesModel();

        $validatedData = $request->validate([
            'proyecto' => 'required|max:255',
            'numero_de_parte' => 'required|max:255',
            'descripcion' => 'required|max:255',
            'um' => 'required|max:255|not_in:Unidades',
        ]);

        $partes->proyecto = $request->proyecto;
        $partes->numero_de_parte = $request->numero_de_parte;
        $partes->descripcion = $request->descripcion;
        $partes->um = $request->um;
        $partes->active = 1;

        $partes->save();

        return redirect('partes')->with('success', 'Registro creado existosamente');
    }

    public function update(Request $request) {

        PartesModel::where('id', $request->id)->update([
            'numero_de_parte' => $request->numero_de_parte,
            'descripcion' => $request->descripcion,
        ]);

        return back()->with('success', 'Registro actualizado exitosamente');
    }

    public function destroy(Request $request) {
        PartesModel::where('id', $request->id_parte)->update(['active' => 0]);

        return redirect('partes')->with('success', 'Registro eliminado exitosamente');
    }
}
