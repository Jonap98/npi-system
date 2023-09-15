<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\RequerimientosModel;
use App\Models\BomsModel;
use App\Models\MovimientosModel;
// use App\Models\test\MovimientosModel;
use App\Models\SolicitudesModel;
use App\Models\test\PartesModel;
use App\Models\CantidadUbicacionesModel;
use App\Models\InventarioModel;
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
        ->where('status', '!=', 'ELIMINADO')
        ->orderBy('id', 'desc')
        ->get();

        foreach ($requerimientos as $requerimiento) {
            $kit = RequerimientosModel::select(
                'kit_nombre',
                'team'
            )
            ->where('folio', $requerimiento->folio)
            ->first();

            $requerimiento->kit_nombre = $kit->kit_nombre ?? '';
            $requerimiento->team = $kit->team ?? '';
        }

        return view('requerimientos.solicitudes.index', array('requerimientos' => $requerimientos));
    }

    public function filter(Request $request) {
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
            'kit_nombre',
            'descripcion',
            'cantidad_requerida',
            'cantidad_ubicacion',
            'solicitante',
            'comentario',
            'status',
            'ubicacion'
        )
        ->where('folio', $folio)
        ->get();

        $status = SolicitudesModel::select('status')->where('folio', $folio)->first();

        $indice = 0;

        // Iterar en cada requerimiento para obtener todos los detalles
        foreach ($requerimientos as $req) {
            $ubicaciones = InventarioModel::select(
                'id',
                'numero_de_parte',
                'ubicacion',
                'cantidad',
                'palet',
            )
            ->where('numero_de_parte', $req->num_parte)
            ->where('cantidad', '>', 0)
            ->orderBy('ubicacion', 'asc')
            ->get();

            $req->cantidad_ubicacion = $ubicaciones;

            foreach ($req->cantidad_ubicacion as $ubicacion) {
                $ubicacion->element_index = $indice;
                $indice++;
            }

            // 3- Obtener las cantidades solicitadas (las que ya fueron surtidas)
            $cantidad_ubicaciones = CantidadUbicacionesModel::select(
                'id',
                'folio_solicitud',
                'num_parte',
                'cantidad',
                'ubicacion',
                'palet',
                'status'
            )
            ->where('folio_solicitud', $req->folio)
            ->where('num_parte', $req->num_parte)
            ->where('id_requerimiento', $req->id)
            ->get();

            $req->cantidad_solicitada = $cantidad_ubicaciones;
        }

        return view('requerimientos.solicitudes.details', array('requerimientos' => $requerimientos, 'status' => $status->status, 'folio' => $folio));
    }

    public function detailsOLD($folio) {
        // 1- Obtener todos los requerimientos de ese folio
        $requerimientos = RequerimientosModel::select(
            'id',
            'folio',
            'num_parte',
            'kit_nombre',
            'descripcion',
            'cantidad_requerida',
            'cantidad_ubicacion',
            'solicitante',
            'comentario',
            'status',
            'ubicacion'
        )
        ->where('folio', $folio)
        ->get();

        $status = SolicitudesModel::select('status')->where('folio', $folio)->first();

        $indice = 0;

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
            ->orderBy('ubicacion', 'asc')
            ->get();

            // Agrupar las cantidades solicitadas
            $ubicaciones_temp = [];
            if(count($solicitadas) > 0) {
                array_push($ubicaciones_temp, $solicitadas[0]);
            }

            for($j = 1; $j < count($solicitadas); $j++) {
                $repetida = false;
                for($k = 0; $k < count($ubicaciones_temp); $k++) {
                    if($solicitadas[$j]->numero_de_parte == $ubicaciones_temp[$k]->numero_de_parte && $solicitadas[$j]->ubicacion == $ubicaciones_temp[$k]->ubicacion && $solicitadas[$j]->palet == $ubicaciones_temp[$k]->palet) {
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
            ->where('id_requerimiento', $requerimientos[$i]->id)
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
                        // $suma = $solicitudes_list[$m]->cantidad += $solicitudes[$l]->cantidad;
                    }
                }
                if(!$seRepitio) {
                    array_push($solicitudes_list, $solicitudes[$l]);
                }
            }


            $requerimientos[$i]->solicitudes = $solicitudes_list;


            foreach ($requerimientos[$i]->ubicaciones as $ubicacion) {
                $ubicacion->element_index = $indice;
                $indice++;

                foreach ($requerimientos[$i]->solicitudes as $solicitud) {
                    if($ubicacion->ubicacion == $solicitud->ubicacion && $ubicacion->palet == $solicitud->palet) {
                        // $ubicacion->cantidad -= $solicitud->cantidad;
                    }
                }
            }
        }

        return view('requerimientos.solicitudes.details', array('requerimientos' => $requerimientos, 'status' => $status->status, 'folio' => $folio));
    }

    public function updateStatus(Request $request) {
        $solicitud = RequerimientosModel::where('folio', $request->folio)->update(['status' => 'PREPARADO', 'updated_at' => Carbon::now()->subHours(1)]);
        $solicitud = SolicitudesModel::where('folio', $request->folio)->update(['status' => 'PREPARADO', 'updated_at' => Carbon::now()->subHours(1)]);

        return response([
            'msg' => 'Status actualizado exitosamente'
        ]);
    }

    public function preparar(Request $request) {

        // $ubicacionesTst = [];
        // $movimientosTst = [];
        $currentUser = Auth::user()->username;
        foreach ($request->cantidades as $cantidad) {
            if( $cantidad['cantidad'] > 0 ) {
                $proyecto = PartesModel::select(
                    'proyecto'
                )
                ->where('numero_de_parte', $cantidad['num_parte'])
                ->first();

                $cantidad_ubicacion = new CantidadUbicacionesModel();

                $cantidad_ubicacion->folio_solicitud = $cantidad['folio'];
                $cantidad_ubicacion->num_parte = $cantidad['num_parte'];
                $cantidad_ubicacion->cantidad = $cantidad['cantidad'];
                $cantidad_ubicacion->ubicacion = $cantidad['ubicacion'];
                $cantidad_ubicacion->palet = $cantidad['palet'];
                $cantidad_ubicacion->status = 'PREPARADO';
                $cantidad_ubicacion->id_requerimiento = $cantidad['id_requerimiento'];
                $cantidad_ubicacion->created_at = Carbon::now()->subHours(1);
                $cantidad_ubicacion->updated_at = Carbon::now()->subHours(1);

                // array_push($ubicacionesTst, $cantidad_ubicacion);

                $cantidad_ubicacion->save();

                $movimiento = new MovimientosModel();

                $movimiento->proyecto = $proyecto->proyecto ?? '';
                $movimiento->cantidad = $cantidad['cantidad'];
                $movimiento->tipo = 'Salida';
                $movimiento->comentario = 'Requerimiento de material con folio: '.$cantidad['folio'];
                $movimiento->fecha_registro = Carbon::now()->subHours(1);
                $movimiento->id_parte = '0';
                $movimiento->numero_de_parte = $cantidad['num_parte'];
                $movimiento->ubicacion = $cantidad['ubicacion'];
                $movimiento->palet = $cantidad['palet'];
                $movimiento->created_at = Carbon::now()->subHours(1);
                $movimiento->updated_at = Carbon::now()->subHours(1);
                $movimiento->usuario = $currentUser;

                // array_push($movimientosTst, $movimiento);

                $movimiento->save();

                // Obtiene el inventario de esa ubicación
                $inventario = InventarioModel::select(
                    'id',
                    'numero_de_parte',
                    'cantidad',
                    'ubicacion',
                    'palet',
                )
                ->where('numero_de_parte', $cantidad['num_parte'])
                ->where('ubicacion', $cantidad['ubicacion'])
                ->where('palet', $cantidad['palet'])
                ->first();

                // Resta la cantidad al inventario
                InventarioModel::where( 'id', $inventario->id )
                    ->update([ 'cantidad' => $inventario->cantidad -= $cantidad['cantidad'] ]);
            }

        }

        // Se actualiza el status en la tabla de solicitudes y su requerimiento correspondiente
        $solicitud = RequerimientosModel::where('folio', $request->cantidades[0]['folio'])->update(['status' => 'PREPARADO', 'updated_at' => Carbon::now()->subHours(1)]);
        $solicitud = SolicitudesModel::where('folio', $request->cantidades[0]['folio'])->update(['status' => 'PREPARADO', 'updated_at' => Carbon::now()->subHours(1)]);

        return response([
            'msg' => 'Requerimiento surtido exitosamente',
        ]);

        return response([
            'msg' => 'Wait'
        ]);
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

            $movimiento->proyecto = $proyecto ?? '';
            $movimiento->cantidad = $requerimientosList[$i+1] ?? 0;
            $movimiento->tipo = 'Salida';
            $movimiento->comentario = '';
            $movimiento->fecha_registro = Carbon::now()->subHours(1);
            $movimiento->id_parte = $part->id;
            $movimiento->numero_de_parte = $requerimientosList[$i];
            $movimiento->ubicacion = $requerimientosList[$i+2];
            $movimiento->palet = $requerimientosList[$i+3];
            $movimiento->created_at = Carbon::now()->subHours(1);
            $movimiento->updated_at = Carbon::now()->subHours(1);


            $movimiento->save();

            $i+4;
        }

        $solicitud = SolicitudesModel::where('id', $request->id_solicitud)->update(['status' => 'PREPARADO', 'updated_at' => Carbon::now()->subHours(1)]);

        return back()->with('success', 'La solicitud fue actualizada exitosamente');
    }

    public function updateQty(Request $request) {
        $currentUser = Auth::user()->username;

        // 1- Validar cantidad en inventario
        // 1.1 - Obtiene la cantidad registrada como salida del inventario
        $cantidadRegistrada = CantidadUbicacionesModel::select(
            'folio_solicitud',
            'cantidad'
        )
        ->where('id', $request->cantidad_id)
        ->first();

        // 1.2 - Obtiene el inventario de ese ubicación y palet
        $cantidadInventario = InventarioModel::select(
            'id',
            'cantidad',
            'ubicacion',
            'palet',
        )
        ->where('numero_de_parte', $request->num_parte)
        ->where('ubicacion', $request->ubicacion)
        ->where('palet', $request->palet)
        ->first();


        // Valida que la cantidad ingresada sea mayor a cero y que halla stock en la ubicación, es decir,
        // que sea mayor a la cantid ingresada que en la ubicación
        if( $cantidadInventario->cantidad < 0 || $cantidadInventario->cantidad < $request->cantidad ) {
            return back()->with('error', 'No se puede ajustar la cantidad, no tiene suficiente inventario');
        }

        $cantidadTemporal = $cantidadInventario->cantidad + $request->cantidad_actual;

        $nueva = $cantidadTemporal - $request->cantidad;

        // InventarioModel::where('id', $cantidadInventario->id)->update(['cantidad' => $cantidadTemporal + $request->cantidad]);

        // 2.3- Ajuste de cantidad en ubicación
        CantidadUbicacionesModel::where('id', $request->cantidad_id)->update(['cantidad' => $request->cantidad, 'updated_at' => Carbon::now()->subHours(1)]);
        // $cantidad_ubicaciones = CantidadUbicacionesModel::where('id', $request->cantidad_id)->get();

        $info = PartesModel::select(
            'proyecto'
        )
        ->where('numero_de_parte', $request->num_parte)
        ->first();

        // 2.4- Ajuste de cantidad en movimiento
        $ajuste_salida = new MovimientosModel();

        $ajuste_salida->proyecto = $info->proyecto ?? '';
        $ajuste_salida->cantidad = $request->cantidad_actual;
        $ajuste_salida->tipo = 'Entrada';
        $ajuste_salida->comentario = 'Ajuste de requerimiento con folio: '.$cantidadRegistrada->folio_solicitud;
        $ajuste_salida->fecha_registro = Carbon::now()->subHours(1);
        $ajuste_salida->id_parte = $cantidadInventario->id_parte ?? 0;
        $ajuste_salida->numero_de_parte = $request->num_parte;
        $ajuste_salida->ubicacion = $request->ubicacion;
        $ajuste_salida->palet = $request->palet;
        $ajuste_salida->created_at = Carbon::now()->subHours(1);
        $ajuste_salida->updated_at = Carbon::now()->subHours(1);
        $ajuste_salida->usuario = $currentUser;

        $ajuste_salida->save();

        $ajuste_entrada = new MovimientosModel();

        $ajuste_entrada->proyecto = $info->proyecto ?? '';
        $ajuste_entrada->cantidad = $request->cantidad;
        $ajuste_entrada->tipo = 'Salida';
        $ajuste_entrada->comentario = 'Ajuste de requerimiento con folio: '.$cantidadRegistrada->folio_solicitud;
        $ajuste_entrada->fecha_registro = Carbon::now()->subHours(1);
        $ajuste_entrada->id_parte = $cantidadInventario->id_parte ?? 0;
        $ajuste_entrada->numero_de_parte = $request->num_parte;
        $ajuste_entrada->ubicacion = $request->ubicacion;
        $ajuste_entrada->palet = $request->palet;
        $ajuste_entrada->created_at = Carbon::now()->subHours(1);
        $ajuste_entrada->updated_at = Carbon::now()->subHours(1);
        $ajuste_entrada->usuario = $currentUser;

        $ajuste_entrada->save();
        // 2.4- Ajuste de cantidad en inventario
        InventarioModel::where('id', $cantidadInventario->id)->update(['cantidad' => $nueva, 'updated_at' => Carbon::now()->subHours(1)]);

        return back()->with('success', 'La cantidad fue ajustada exitosamente');
    }

    public function update(Request $request) {
        $status = ($request->action == '1') ? 'PREPARADO' : 'RECIBIDO';

        SolicitudesModel::where('id', $request->id)->update(['status' => $status, 'updated_at' => Carbon::now()->subHours(1)]);
        RequerimientosModel::where('folio', $request->folio)->update(['status' => $status, 'updated_at' => Carbon::now()->subHours(1)]);

        return back()->with('success', 'La solicitud fue actualizada exitosamente');
    }

    public function exportPDF(Request $request) {
        $requerimientos = RequerimientosModel::select(
            'id',
            'num_parte',
            'descripcion',
            'cantidad_requerida',
            'comentario',
            'status',
            'ubicacion'
        )
        ->where('folio', $request->folio)
        ->get();

        $solicitud = RequerimientosModel::select(
            'solicitante',
            'team'
        )
        ->where('folio', $request->folio)
        ->first();

        $kit_nombre = RequerimientosModel::select(
            'kit_nombre',
        )
        ->where('folio', $request->folio)
        ->first();

        foreach ($requerimientos as $req) {
            $status = BomsModel::select(
                'status'
            )
            ->where('num_parte', $req->num_parte)
            ->where('team', $solicitud->team)
            ->first();

            $req->status_bom = substr($status->status ?? '', 0, 8);

            // Proceso para obtener ubicacion y palet sin repetir
            $ubicaciones = DB::table('NPI_movimientos')
            ->select('ubicacion')
            ->where('numero_de_parte', $req->num_parte)
            ->get()
            ->unique('ubicacion');

            $ubicaciones_array = array();
            foreach ($ubicaciones as $ubicacion) {
                $palets = DB::table('NPI_movimientos')
                ->select('palet')
                ->where('numero_de_parte', $req->num_parte)
                ->where('ubicacion', $ubicacion->ubicacion)
                ->get()
                ->unique('palet');


                $palets_array = array();
                foreach ($palets as $palet) {
                    array_push($palets_array, $palet);
                }
                $ubicacion->palets_registrados = $palets_array;

                array_push($ubicaciones_array, $ubicacion);

            }
            $req->ubicaciones_registradas = $ubicaciones_array;
        }

        // Se debe decodificar la respuesta para hacerla compatible con el PDF
        $data = json_decode($requerimientos);
        $count = count($data);

        // Se vuelve a codificar para acceder a ella como arreglo
        $arr = json_decode(json_encode($data), true);

        // $fileName = "Requerimiento de material ".$arr[0]['TIPO']."-".substr($arr[0]['FECHAREAL'], 0, 10).".pdf";
        $fileName = "Requerimiento de material.pdf";

        // Descargar archivo
        $pdf = \PDF::loadView('requerimientos.solicitudes.pdf', array('requerimientos' => $data, 'count' => $count, 'kit' => $kit_nombre->kit_nombre, 'solicitante' => $solicitud->solicitante, 'team' => $solicitud->team, 'folio' => $request->folio));

        return $pdf->download($fileName);
    }

    public function delete(Request $request) {
        SolicitudesModel::where( 'folio', $request->folio )->delete();
        RequerimientosModel::where( 'folio', $request->folio )->delete();

        return response([
            'msg' => 'La solicitud ha sido eliminada exitosamente'
        ]);
    }
}
