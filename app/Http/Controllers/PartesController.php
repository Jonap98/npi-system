<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PartesModel;
use App\Models\UbicacionesModel;
use App\Http\Controllers\PlainLoginController;

use Illuminate\Support\Facades\Auth;

class PartesController extends Controller
{
    var $loginOK = false;

    public function index() {
        // if(Auth::check()) {
        //     return response(['auth' => true]);
            // $partes = PartesModel::get();
            // if(!$loginOK) {
            //     $loginOK = app(PlainLoginController::class)->login();
            // }

            $partes = PartesModel::select(
                'id',
                'numero_de_parte',
                'descripcion',
                'um',
                // 'ubicacion',
                // 'palet',
                // 'fila',
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
        // }
        // return response(['auth' => false]);

    }

    public function create() {
        $partes = PartesModel::get();
        
        $ubicaciones = UbicacionesModel::get();

        return view('partes.create', array('partes' => $partes, 'ubicaciones' => $ubicaciones));
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
        // $partes->ubicacion = $request->ubicacion;
        // $partes->palet = $request->palet;
        // $partes->fila = $request->fila;
        $partes->active = 1;

        $partes->save();
        
        return redirect('partes')->with('success', 'Registro creado existosamente');
    }

    public function update(Request $request, $id) {
        $parte = PartesModel::findOrFail($id);

        $validatedData = $request->validate([
            'numero_de_parte' => 'required|max:255',
            'descripcion' => 'required|max:255',
            'um' => 'required|max:255|not_in:Unidades',
        ]);

        $parte->numero_de_parte = $request->numero_de_parte;
        $parte->descripcion = $request->descripcion;
        $parte->um = $request->um;
        $parte->ubicacion = $request->ubicacion;
        $parte->palet = $request->palet;
        $parte->fila = $request->fila;

        $parte->save();

        return redirect('partes')->with('success', 'Registro actualizado existosamente');
    }

    public function edit($id) {
        $parte = PartesModel::findOrFail($id);

        $ubicaciones = UbicacionesModel::get();

        return view('partes.edit', array(
            'proyecto',
            'numero_parte',
            'descripcion',
            'um',
            'ubicacion',
            'ubicaciones' => $ubicaciones,
            'action' => action('App\Http\Controllers\PartesController@update', $id),
        ));
    }

    public function destroy($id) {
        \DB::table('NPI_partes')->where('id', $id)->update(['active' => 0 ]); 

        return redirect('partes')->with('success', 'Registro eliminado exitosamente');
    }

    // public function getCookie(Request $request){
    //     $value = $request->cookie('name');
    //     echo $value;
    //  }
}
