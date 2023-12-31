<?php

namespace App\Http\Controllers\requerimientos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BomsModel;
use App\Models\SolicitudesModel;
use App\Models\RequerimientosModel;
use Illuminate\Support\Facades\Auth;

class MakeController extends Controller
{
    public function index($id) {
        $team = BomsModel::select(
            'kit_nombre',
            'num_parte',
            'team',
            'status'
        )
        ->where('id', $id)
        ->first();

        $makes = BomsModel::select(
            'id',
            'kit_nombre',
            'kit_descripcion',
            'num_parte',
            'status',
            'team',
            'padre',
        )
        ->where('team', $team->team)
        ->where('kit_nombre', $team->kit_nombre)
        ->where('status', 'like', 'MAKE%')
        ->get();

        $list = [];
        foreach ($makes as $make) {

            $detailsMake = BomsModel::select(
                'status'
            )
            ->where('id', $make->id)
            ->first();

            $another = BomsModel::select(
                'id',
                'kit_nombre',
                'num_parte',
                'kit_descripcion',
                'cantidad',
                'status',
            )
            ->where('team', $make->team)
            ->where('kit_nombre', $detailsMake->status)
            ->orderBy('kit_descripcion', 'asc')
            ->get();

            if($another) {
                $make->details = $another;
            }

            if(count($make->details) > 0) {
                array_push($list, $make);
            }
        }

        return view('requerimientos.porModelo.make.index', array('makes' => $list, 'make' => $team->kit_nombre, 'temp' => $team->team, 'padre_id' => $id));
    }

    public function getInfo(Request $request) {

        $team = BomsModel::select(
            'kit_nombre',
            'num_parte',
            'team',
            'status'
        )
        ->where('id', $request->padre_id)
        ->first();

        $makes = BomsModel::select(
            'id',
            'kit_nombre',
            'kit_descripcion',
            'num_parte',
            'status',
            'team',
            'ubicacion',
            'cantidad'
        )
        ->where('team', 72)
        ->where('kit_nombre', $request->kit_status)
        ->where('status', 'like', 'MAKE%')
        ->where('ubicacion', 'like', '%'.$team->kit_nombre.'%')
        // ->where('ubicacion', 'like', '%PANTRY%')
        ->get();

        return response([
            'concat' => '%'.$team->kit_nombre.'%',
            'all' => $request->all(),
            'data' => $makes,
            'padre_id' => $request->padre_id,
            'padre' => $team
        ]);

    }

    public function exportPDF(Request $request) {

        $team = BomsModel::select(
            'kit_nombre',
            'num_parte',
            'team',
            'status'
        )
        ->where('id', $request->padre_id)
        ->first();

        $makes = BomsModel::select(
            'id',
            'kit_nombre',
            'kit_descripcion',
            'num_parte',
            'status',
            'team',
            'ubicacion',
            'cantidad'
        )
        ->where('team', 72)
        ->where('kit_nombre', $request->kit_status)
        ->where('status', 'like', 'MAKE%')
        ->where('ubicacion', 'like', '%'.$team->kit_nombre.'%')
        // ->where('ubicacion', 'like', '%PANTRY%')
        ->get();

        $data = json_decode($makes);
        $count = count($data);

        $arr = json_decode(json_encode($data), true);

        $fileName = "Vista previa de material.pdf";

        // $pdf = \PDF::loadView('', aray())

    }

    public function store(Request $request) {
        $ultimoFolio = SolicitudesModel::select(
            'id',
            'folio'
        )
        ->orderBy('id', 'desc')
        ->first();

        if(!$ultimoFolio) {
            $folio = 0;
        } else {
            $folio = $ultimoFolio->folio + 1;
        }

        // 2 - Se crea la solicitud con nuevo folio
        $solicitud = new SolicitudesModel();

        $solicitud->folio = $folio;
        $solicitud->solicitante = Auth::user()->name;
        $solicitud->status = 'SOLICITADO';

        $solicitud->save();

        foreach ($request->valores as $valor) {
            $requerimiento = new RequerimientosModel();

            $requerimiento->folio = $folio;
            $requerimiento->num_parte = $valor['num_part'];
            $requerimiento->kit_nombre = $valor['kit_nombre'];
            $requerimiento->kit_num_parte = '';
            $requerimiento->descripcion = $valor['descripcion'];
            $requerimiento->cantidad_requerida = ($request->cantidad * $valor['cantidad']);
            // Validar si seguirá siendo necesario este campo en la BD
            $requerimiento->cantidad_ubicacion = 1000;
            $requerimiento->solicitante = Auth::user()->username;
            $requerimiento->comentario = '';
            $requerimiento->status = 'SOLICITADO';
            $requerimiento->ubicacion = $valor['ubicacion'];
            $requerimiento->team = $valor['team'];
            $requerimiento->active = 1;

            $requerimiento->save();
        }

        return response([
            'msg' => '¡Solicitud realizada exitosamente!'
        ]);


    }

    public function generatePDF(Request $request) {

        $arr = json_decode($request->kit, true);

        $fileName = "Vista previa.pdf";

        // Descargar archivo
        $pdf = \PDF::loadView('requerimientos.porModelo.make.pdf', array('requerimientos' => $arr['details']));

        return $pdf->download($fileName);

    }

}

