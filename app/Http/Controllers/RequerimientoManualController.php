<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\BomsModel;
use App\Models\RequerimientosModel;
use App\Models\PartesModel;
use App\Models\SolicitudesModel;

class RequerimientoManualController extends Controller
{
    public function index() {
        // Para selección de Kits en la solicitud
        $kits = BomsModel::select(
            'id',
            'kit_nombre',
            'kit_descripcion',
            'um',
            'nivel',
            'num_parte'
        )
        ->where('nivel', '=', '1')
        ->get();


        // $modelosSeleccionados = DB::connection('mysql')->table('npi_boms')->paginate();
        $modelosSeleccionados = BomsModel::select(
            'id',
            'kit_nombre',
            'kit_descripcion',
            'um',
            'nivel',
            'num_parte'
        )
        ->get();
        
        return view('requerimientos.manual.index', array('kits' => $kits, 'modelos' => $modelosSeleccionados, 'partes' => $modelosSeleccionados));
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

        // return response([
        //     'data' => $primerRegistro
        // ]);
        
        $id = $primerRegistro->id;
        $modelosSeleccionados = array();
        array_push($modelosSeleccionados, $primerRegistro);

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

            if($model->nivel == '1') {
                // return response([
                //     'data' => 'Es uno',
                // ]);
                array_push($modelosSeleccionados, $model);
            }

        } while ($primerRegistro->nivel != $model->nivel);
        array_shift($modelosSeleccionados);

        // return response([
        //     'primer id' => $primerRegistro->id,
        //     'ultimo id' => $id,
        //     'data' => $modelosSeleccionados,
        // ]);

        // return view('requerimientos.porModelo.index', array('modelos' => $modelos, 'modelosSeleccionados' => $modelosSeleccionados));
        return view('requerimientos.porModelo.kits', array('kits' => $modelosSeleccionados));

    }

    public function solicitar(Request $request) {
        // dd($request);
        $ultimoFolio = SolicitudesModel::select(
            'id',
            'folio'
        )
        ->orderBy('id', 'desc')
        ->first();
        
        // Primer registro, no hay folio existente
        if(!$ultimoFolio) {
            $folio = 0;
        } else {
            $folio = $ultimoFolio->folio + 1;
        }

        // return response([
        //     'data' => $ultimoFolio,
        //     'folio' => $folio
        // ]);

        $solicitud = new SolicitudesModel();

        $solicitud->folio = $folio;
        $solicitud->solicitante = $request->solicitante;
        $solicitud->status = 'SOLICITADO';

        $solicitud->save();


        for($i = 0; $i < $request->counter; $i++) {

            
            $num_parte = 'num_parte'.$i;
            $cantidad_requerida = 'cantidad_requerida'.$i;
            $kit_descripcion = 'kit_descripcion'.$i;
            
            if($request->$num_parte) {

                $model = BomsModel::select(
                    'id',
                    'kit_nombre',
                    'kit_descripcion',
                    'nivel',
                    'num_parte'
                )
                ->where('num_parte', $request->$num_parte)
                ->first();
                
                // return response([
                //     'desc' => $model->kit_descripcion,
                //     'data' => $model
                // ]);

                $requerimiento = new RequerimientosModel();
                
                $requerimiento->folio = $folio;
                $requerimiento->num_parte = $request->$num_parte;
                // $requerimiento->descripcion = consulta bom
                $requerimiento->descripcion = $model->kit_descripcion ?? '';
                $requerimiento->cantidad_requerida = $request->$cantidad_requerida;
                // $requerimiento->cantidad_ubicacion = consulta inventario
                $requerimiento->cantidad_ubicacion = 1000;
                $requerimiento->solicitante = $request->solicitante;
                $requerimiento->comentario = '';
                $requerimiento->status = 'SOLICITADO';

                // return response([
                //     'data' => $requerimiento
                // ]);

                $requerimiento->save();
            }

        }

        return back()->with('success', 'La solicitud fue creada exitosamente');

    }
}
