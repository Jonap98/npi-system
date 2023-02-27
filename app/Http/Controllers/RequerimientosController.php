<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequerimientosModel;
use App\Models\PartesModel;
use App\Models\SolicitudesModel;
use App\Models\BomsModel;
use App\Models\UbicacionesModel;

class RequerimientosController extends Controller
{
    public function create() {
        $partes = PartesModel::get();

        return view('requerimientos.requerimientos', array('partes' => $partes));
    }

    public function getKit($kit_nombre) {
        $numbers = BomsModel::select(
            'id',
            'kit_nombre',
            'num_parte'
        )
        ->where('kit_nombre', $kit_nombre)
        ->get();

        return response([
            'data' => $numbers
        ]);
    }

}
