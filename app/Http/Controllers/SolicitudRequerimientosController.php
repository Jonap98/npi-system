<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\RequerimientosModel;
use App\Models\MovimientosModel;
use App\Models\SolicitudesModel;
use App\Models\PartesModel;
use App\Models\CantidadUbicacionesModel;
use Carbon\Carbon;

class SolicitudRequerimientosController extends Controller
{
    public function index() {
        $requerimientos = SolicitudesModel::select(
            'id',
            'folio',
            'solicitante',
            'status',
            'created_at as fecha'
        )
        ->where('status', '!=', 'RECIBIDO')
        ->orderBy('id', 'desc')
        ->get();

        // return response([
        //     'data' => $requerimientos
        // ]);

        return view('requerimientos.solicitudes.index', array('requerimientos' => $requerimientos));
    }

    public function filter(Request $request) {
        // dd($request);
        // return response([
        //     'status' => $request->status
        // ]);
        $requerimientos = SolicitudesModel::select(
            'id',
            'folio',
            'solicitante',
            'status',
            'created_at as fecha'
        )
        ->where('status', $request->status)
        ->orderBy('id', 'desc')
        ->get();

        return view('requerimientos.solicitudes.index', array('requerimientos' => $requerimientos));
    }


    public function details($folio) {
        // 1- Obtener todos los requerimientos de ese folio
        $requerimientos = RequerimientosModel::select(
            'id',
            'folio',
            'num_parte',
            'descripcion',
            'cantidad_requerida',
            'cantidad_ubicacion',
            'solicitante',
            'comentario',
            'status',
        )
        ->where('folio', $folio)
        ->get();

        $status = SolicitudesModel::select('status')->where('folio', $folio)->first();

        // Iterar en cada requerimiento para obtener los detalles
        for($i = 0; $i < count($requerimientos); $i++) {

            $inventarioIndividual = 0;

            // 2- Obtener las cantidades solicitadas
            // Consulta de material por ubicación de la tabla de movimientos
            // para ver de donde se tomará el material, según su disponibilidad
            $solicitadas = MovimientosModel::select(
                'id',
                'numero_de_parte',
                'ubicacion',
                'cantidad',
                'tipo',
                'palet',
            )
            ->where('numero_de_parte', $requerimientos[$i]->num_parte)
            ->get();

            // Agrupar las cantidades solicitadas
            $ubicaciones_temp = [];
            if(count($solicitadas) > 0) {
                array_push($ubicaciones_temp, $solicitadas[0]);
            }

            for($j = 1; $j < count($solicitadas); $j++) {
                $repetida = false;
                for($k = 0; $k < count($ubicaciones_temp); $k++) {
                    if($solicitadas[$j]->numero_de_parte == $ubicaciones_temp[$k]->numero_de_parte && $solicitadas[$j]->palet == $ubicaciones_temp[$k]->palet) {
                        $repetida = true;
                        if($solicitadas[$j]->tipo == 'Entrada') {
                            $ubicaciones_temp[$k]->cantidad += $solicitadas[$j]->cantidad;
                        }

                        if($solicitadas[$j]->tipo == 'Salida') {
                            $ubicaciones_temp[$k]->cantidad -= $solicitadas[$j]->cantidad;
                        }

                        if($ubicaciones_temp[$k]->cantidad < 0) {
                            $ubicaciones_temp[$k]->cantidad = 0;
                        }
                    }
                }
                if(!$repetida) {
                    array_push($ubicaciones_temp, $solicitadas[$j]);
                }
            }

            $requerimientos[$i]->ubicaciones = $ubicaciones_temp;


            // 3- Obtener las cantidades por ubicacion de cada número  de parte
            $solicitudes = CantidadUbicacionesModel::select(
                'id',
                'folio_solicitud',
                'num_parte',
                'cantidad',
                'ubicacion',
                'palet',
                'status'
            )
            ->where('folio_solicitud', $requerimientos[$i]->folio)
            ->where('num_parte', $requerimientos[$i]->num_parte)
            ->get();

            
            
            $solicitudes_list = [];
            if(count($solicitudes) > 0) {
                array_push($solicitudes_list, $solicitudes[0]);
            }

            // Sumar las cantidades agrupandolas por ubicacion y palet
            for($l = 1; $l < count($solicitudes); $l++) {
                $seRepitio = false;
                for($m = 0; $m < count($solicitudes_list); $m++) {
                    if($solicitudes[$l]->ubicacion == $solicitudes_list[$m]->ubicacion && $solicitudes[$l]->palet == $solicitudes_list[$m]->palet) {
                        $seRepitio = true;
                        $suma = $solicitudes_list[$m]->cantidad += $solicitudes[$l]->cantidad;
                    }
                }
                if(!$seRepitio) {
                    array_push($solicitudes_list, $solicitudes[$l]);
                }
            }


            $requerimientos[$i]->solicitudes = $solicitudes_list;


            foreach ($requerimientos[$i]->ubicaciones as $ubicacion) {
                foreach ($requerimientos[$i]->solicitudes as $solicitud) {
                    if($ubicacion->ubicacion == $solicitud->ubicacion && $ubicacion->palet == $solicitud->palet) {
                        $ubicacion->cantidad -= $solicitud->cantidad;
                    }
                }
            }

            // return response([
            //     'data' => $requerimientos
            // ]);

        }
        
        return view('requerimientos.solicitudes.details', array('requerimientos' => $requerimientos, 'status' => $status->status, 'folio' => $folio));
    }

    public function preparar(Request $request) {
        dd($request);

        $requerimientos = $request->all();
        $requerimientosList = [];
        
        unset($requerimientos['_token']);
        unset($requerimientos['requerimientos_length']);
        unset($requerimientos['folio']);
        
        foreach ($requerimientos as $requerimiento) {
            array_push($requerimientosList, $requerimiento);
        }
        
        $counter = count($requerimientosList);

        for($i = 0; $i < count($requerimientosList); $i ++) {

            $part = PartesModel::select(
                'id',
                'proyecto'
            )
            ->where('numero_de_parte', $requerimientosList[$i])
            ->first();

            $proyecto = $part->proyecto ?? '';
            $id = $part->id ?? '';

            $movimiento = new CantidadUbicacionesModel();

            $movimiento->folio_solicitud = $request->folio;
            $movimiento->num_parte = $requerimientosList[$i];
            $movimiento->cantidad = $requerimientosList[$i+3] ?? 0;
            $movimiento->ubicacion = $requerimientosList[$i+1];
            $movimiento->palet = $requerimientosList[$i+2];
            $movimiento->status = 'PREPARADO';

            $movimiento->save();

            $movimiento = new MovimientosModel();

            $movimiento->proyecto = $proyecto;
            $movimiento->cantidad = $requerimientosList[$i+3] ?? 0;
            $movimiento->tipo = 'Salida';
            $movimiento->comentario = 'Requerimiento de material con folio: '.$request->folio;
            $movimiento->fecha_registro = Carbon::now();
            $movimiento->id_parte = $id;
            $movimiento->numero_de_parte = $requerimientosList[$i];
            $movimiento->ubicacion = $requerimientosList[$i+1];
            $movimiento->palet = $requerimientosList[$i+2];

            $movimiento->save();

            $i += 3;

        }

        $solicitud = RequerimientosModel::where('folio', $request->folio)->update(['status' => 'PREPARADO']);
        $solicitud = SolicitudesModel::where('folio', $request->folio)->update(['status' => 'PREPARADO']);

        return back()->with('success', 'La solicitud fue actualizada exitosamente');
    }


    // Validar funcionamiento
    public function confirmar(Request $request) {
        
        $requerimientos = $request->all();
        $requerimientosList = [];
        
        unset($requerimientos['_token']);
        unset($requerimientos['requerimientos_length']);
        unset($requerimientos['id_solicitud']);
        
        foreach ($requerimientos as $requerimiento) {
            array_push($requerimientosList, $requerimiento);
        }
        
        $counter = count($requerimientosList);

        for($i = 0; $i < $counter; $i ++) {

            $part = PartesModel::select(
                'id',
                'proyecto'
            )
            ->where('numero_de_parte', $requerimientosList[$i])
            ->first();

            $proyecto = $part->proyecto ?? '';

            $movimiento = new MovimientosModel();

            $movimiento->proyecto = $proyecto;
            $movimiento->cantidad = $requerimientosList[$i+1] ?? 0;
            $movimiento->tipo = 'Salida';
            $movimiento->comentario = '';
            $movimiento->fecha_registro = Carbon::now();
            $movimiento->id_parte = $part->id;
            $movimiento->numero_de_parte = $requerimientosList[$i];
            $movimiento->ubicacion = $requerimientosList[$i+2];
            $movimiento->palet = $requerimientosList[$i+3];

            $movimiento->save();

            $i+4;
        }

        $solicitud = SolicitudesModel::where('id', $request->id_solicitud)->update(['status' => 'PREPARADO']);

        return back()->with('success', 'La solicitud fue actualizada exitosamente');
    }

    public function updateQty(Request $request) {
        // dd($request);
        return response([
            'data' => $request->all()
        ]);
        // $movimientos = MovimientosModel::select(
        //     'id',
        //     'numero_de_parte',
        //     'cantidad',
        //     'tipo'
        // )
        // ->where('numero_de_parte', $request->num_parte)
        // ->get();


        // Calculando el inventario para descontar

        $solicitadas = DB::table('NPI_movimientos')
            ->join('NPI_partes', 'NPI_partes.id', '=', 'NPI_movimientos.id_parte')
            ->select(
                'NPI_movimientos.id',
                'NPI_movimientos.proyecto',
                'NPI_movimientos.cantidad',
                'NPI_movimientos.comentario',
                'NPI_movimientos.tipo',
                'NPI_movimientos.fecha_registro',
                'NPI_movimientos.ubicacion', 
                'NPI_movimientos.palet', 
                'NPI_movimientos.fila',
                'NPI_partes.id',
                'NPI_partes.numero_de_parte',
                'NPI_partes.descripcion',
                'NPI_partes.um',
            )
            ->where('NPI_partes.numero_de_parte', $request->num_parte)
            // ->orderBy('NPI_movimientos.fecha_registro', 'asc')
            ->orderBy('NPI_movimientos.id_parte', 'desc')
            ->orderBy('NPI_movimientos.tipo', 'asc')
            ->get();


        // $solicitadas = MovimientosModel::select(
        //     'id',
        //     'numero_de_parte',
        //     'ubicacion',
        //     'cantidad',
        //     'tipo',
        //     'palet',
        // )
        // ->where('numero_de_parte', $request->num_parte)
        // ->get();

        // return response([
        //     'data' => $solicitadas
        // ]);

        // Agrupar las cantidades solicitadas
        $ubicaciones_temp = [];
        if(count($solicitadas) > 0) {
            array_push($ubicaciones_temp, $solicitadas[0]);
        }

        for($j = 1; $j < count($solicitadas); $j++) {
            $repetida = false;
            for($k = 0; $k < count($ubicaciones_temp); $k++) {
                if($solicitadas[$j]->numero_de_parte == $ubicaciones_temp[$k]->numero_de_parte && $solicitadas[$j]->palet == $ubicaciones_temp[$k]->palet) {
                    $repetida = true;
                    if($solicitadas[$j]->tipo == 'Entrada') {
                        $ubicaciones_temp[$k]->cantidad += $solicitadas[$j]->cantidad;
                    }

                    if($solicitadas[$j]->tipo == 'Salida') {
                        $ubicaciones_temp[$k]->cantidad -= $solicitadas[$j]->cantidad;
                    }

                    if($ubicaciones_temp[$k]->cantidad < 0) {
                        $ubicaciones_temp[$k]->cantidad = 0;
                    }
                }
            }
            if(!$repetida) {
                array_push($ubicaciones_temp, $solicitadas[$j]);
            }
        }



        return response([
            'cantidad' => $request->cantidad,
            'ubicaciones_temp' => $ubicaciones_temp,
            'solicitadas' => $solicitadas
        ]);


        
        // dd($request);
        if(count($movimientos) == 0) {
            return back()->with('error', 'No hay inventario para ese número de parte');
        }

        return response([
            'num_parte' => $request->num_parte,
            'data' => $movimientos
        ]);
    }

    public function update(Request $request) {
        $status = ($request->action == '1') ? 'PREPARADO' : 'RECIBIDO';

        $solicitud = SolicitudesModel::where('id', $request->id)->update(['status' => $status]);

        return back()->with('success', 'La solicitud fue actualizada exitosamente');
    }

    public function exportPDF(Request $request) {
        $requerimientos = RequerimientosModel::select(
            'id',
            'folio',
            'num_parte',
            'descripcion',
            'cantidad_requerida',
            'cantidad_ubicacion',
            'solicitante',
            'comentario',
            'status',
            'created_at',
        )
        ->where('folio', $request->folio)
        ->get();

        // Se debe decodificar la respuesta para hacerla compatible con el PDF
        $data = json_decode($requerimientos);
        $count = count($data);
        
        // Se vuelve a codificar para acceder a ella como arreglo
        $arr = json_decode(json_encode($data), true);
        
        // $fileName = "Requerimiento de material ".$arr[0]['TIPO']."-".substr($arr[0]['FECHAREAL'], 0, 10).".pdf";
        $fileName = "Requerimiento de material.pdf";
        
        // Descargar archivo
        $pdf = \PDF::loadView('requerimientos.solicitudes.pdf', array('requerimientos' => $data, 'count' => $count));
        
        return $pdf->download($fileName);
    }
}
