<?php

namespace App\Http\Controllers\requerimientos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BomsModel;
use App\Models\UbicacionesModel;

class ModelosController extends Controller
{
    public function index() {
        $modelos = BomsModel::select(
            'id',
            'kit_nombre',
            'num_parte'
        )
        ->where('status', 'SKU')
        ->get();

        $ubicaciones = UbicacionesModel::select(
            'ubicacion'
        )
        ->where('tipo', '!=', 'NPI')
        ->get();

        return view('requerimientos.porModelo.index', array('modelos' => $modelos, 'ubicaciones' => $ubicaciones));
    }
}
