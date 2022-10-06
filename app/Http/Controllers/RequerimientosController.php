<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequerimientosModel;
use App\Models\PartesModel;

class RequerimientosController extends Controller
{
    public function index() {
        $requerimientos = RequerimientosModel::get();

        // return response([
        //     'result' => true,
        //     'data' => $requerimientos
        // ]);

        return view('requerimientos.consulta', array('requerimientos' => $requerimientos));
    }

    public function create() {
        $partes = PartesModel::get();

        return view('requerimientos.requerimientos', array('partes' => $partes));
    }
}
