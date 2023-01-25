<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequerimientosModel;
use App\Models\PartesModel;
use App\Models\SolicitudesModel;
use App\Models\BomsModel;

class RequerimientosController extends Controller
{
    public function index() {
        $modelos = BomsModel::select(
            'id',
            'kit_nombre',
            'nivel',
            'num_parte'
        )
        ->where('nivel', '=', '0')
        ->get();

        


        // return response([
        //     'result' => true,
        //     'data' => $requerimientos
        // ]);

        // return view('requerimientos.consulta', array('requerimientos' => $requerimientos));
        return view('requerimientos.porModelo.index', array('modelos' => $modelos));
    }

    public function create() {
        $partes = PartesModel::get();

        return view('requerimientos.requerimientos', array('partes' => $partes));
    }

    public function store(Request $request) {
        return redirect('requerimientos.requerimientos')->with('success', 'El requerimiento fue creado exitosamente');
    }

    public function modelo() {
        $modelos = BomsModel::select(
            'id',
            'kit_nombre',
            'nivel',
            'num_parte'
        )
        ->where('nivel', '=', '1')
        ->get();

        return view('requerimientos.porModelo.index', array('modelos' => $modelos));
    }

    public function kit($kit) {
        $primerRegistro = BomsModel::select(
            'id',
            'kit_nombre',
            'nivel',
            'num_parte'
        )
        ->where('num_parte', $kit)
        ->where('nivel', '=', '0')
        ->first();
        
        $id = $primerRegistro->id;
        $modelosSeleccionados = array();
        array_push($modelosSeleccionados, $primerRegistro);

        // $last = '';

        do {
            $id++;
            $model = BomsModel::select(
                'id',
                'kit_nombre',
                'nivel',
                'num_parte'
            )
            ->where('id', $id)
            // ->where('nivel', '=', '1')
            ->first();
            // $id++;

            // if(!$model) {
            //     return response([
            //         'msg' => 'No data',
            //         'data' => $last
            //     ]);
            // }

            // $last = $model;

            if($model) {
                if($model->nivel == '1') {
                    // return response([
                    //     'data' => 'Es uno',
                    // ]);
                    array_push($modelosSeleccionados, $model);
                }
            }

            // array_push($modelosSeleccionados, $model);
        } while ($model && $primerRegistro->nivel != $model->nivel);
        array_shift($modelosSeleccionados);


        return view('requerimientos.porModelo.kits', array('kits' => $modelosSeleccionados, 'modelosSeleccionados' => $modelosSeleccionados));
    }

    public function details($kit) {
        return view('requerimientos.porModelo.details');
    }

    public function solicitar(Request $request) {
        // dd($request);
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

        $solicitud = new SolicitudesModel();

        $solicitud->folio = $folio;
        $solicitud->solicitante = $request->solicitante;
        $solicitud->status = 'SOLICITADO';

        $solicitud->save();

        // START

        $primerRegistro = BomsModel::select(
            'id',
            'kit_nombre',
            'nivel',
            'num_parte'
        )
        ->where('num_parte', $request->num_parte)
        ->where('nivel', '=', '1')
        ->first();

        $id = $primerRegistro->id;
        $modelosSeleccionados = array();
        array_push($modelosSeleccionados, $primerRegistro);

        do {
            $id++;
            $model = BomsModel::select(
                'id',
                'kit_nombre',
                'kit_descripcion',
                'nivel',
                'num_parte',
                'cantidad',
                'requerido'
            )
            ->where('id', $id)
            ->first();

            // return response([
            //     'data' => $model
            // ]);

            if($model->requerido == 1) {
                $requerimiento = new RequerimientosModel();
                
                $requerimiento->folio = $folio;
                $requerimiento->num_parte = $model->num_parte;
                // $requerimiento->descripcion = consulta bom
                $requerimiento->descripcion = $model->kit_descripcion;
                $requerimiento->cantidad_requerida = ($request->cantidad * $model->cantidad);
                // $requerimiento->cantidad_ubicacion = consulta inventario
                $requerimiento->cantidad_ubicacion = 1000;
                $requerimiento->solicitante = $request->solicitante;
                $requerimiento->comentario = '';
                $requerimiento->status = 'SOLICITADO';
    
                $requerimiento->save();
            }


            array_push($modelosSeleccionados, $model);
        } while ($primerRegistro->nivel != $model->nivel);
        array_shift($modelosSeleccionados);

        // END
        return redirect('solicitudes/requerimientos')->with('success', 'La solicitud fue creada exitosamente');

    }

}
