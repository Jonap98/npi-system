<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\RequerimientosModel;
use App\Models\BomsModel;
// use App\Models\MovimientosModel;
use App\Models\test\MovimientosModel;
use App\Models\SolicitudesModel;
use App\Models\test\PartesModel;
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
                foreach ($requerimientos[$i]->solicitudes as $solicitud) {
                    if($ubicacion->ubicacion == $solicitud->ubicacion && $ubicacion->palet == $solicitud->palet) {
                        // $ubicacion->cantidad -= $solicitud->cantidad;
                    }
                }
            }
        }
        
        return view('requerimientos.solicitudes.details', array('requerimientos' => $requerimientos, 'status' => $status->status, 'folio' => $folio));
    }

    public function preparar(Request $request) {

        $requerimientos = $request->all();
        $requerimientosList = [];

        // Elimina del request los datos que no se necesitan
        unset($requerimientos['_token']);
        unset($requerimientos['requerimientos_length']);
        unset($requerimientos['folio']);

        // Agrega los datos a una lista para poder iterarla de manera más sencilla
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

            // Ingresa el registro de la cantidad que se requiere, a la tabla de Cantidad_ubicaciones
            // De manera que permite una mejor visualización y uso, ya que esa cantidad se registrará posteriormente 
            // en la tabla de movimientos como una salida
            $movimiento = new CantidadUbicacionesModel();

            $movimiento->folio_solicitud = $request->folio;
            $movimiento->num_parte = $requerimientosList[$i];
            $movimiento->cantidad = $requerimientosList[$i+3] ?? 0;
            $movimiento->ubicacion = $requerimientosList[$i+1];
            $movimiento->palet = $requerimientosList[$i+2];
            $movimiento->status = 'PREPARADO';

            $movimiento->save();

            // Realiza el descuento de ese material, del inventario, solo si se hay requerimiento de ese material, es decir,
            // si la cantidad solicitada en esa ubicación es mayor a cero

            // Actualización, crear el movimiento con valor de cero para poder editarlo después en caso de ser necesario
            // Validar eso
            if($requerimientosList[$i+3]) {
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
            }

            // Se itera de 3 en 3 debido a las propiedades del request, de manera que cada 4° valor, es la cantidad
            $i += 3;

        }

        // Se actualiza el status en la tabla de solicitudes y su requerimiento correspondiente
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

        // 1- Validar cantidad en inventario
        // 1.1 - Obtiene la cantidad registrada como salida del inventario
        $cantidadRegistrada = CantidadUbicacionesModel::select(
            'folio_solicitud',
            'cantidad'
        )
        ->where('id', $request->cantidad_id)
        ->first();

        // 1.2 - Obtiene todos los movimientos de ese número de parte, en esa ubicación y palet
        $cantidadInventario = MovimientosModel::select(
            'proyecto',
            'cantidad',
            'tipo',
            'id_parte'
        )
        ->where('numero_de_parte', $request->num_parte)
        ->where('ubicacion', $request->ubicacion)
        ->where('palet', $request->palet)
        ->get();

        // 1.3 - Calcula el inventario existente
        $enInventario = 0;
        foreach ($cantidadInventario as $inventario) {
            if($inventario->tipo == 'Entrada') {
                $enInventario += $inventario->cantidad;
            }

            if($inventario->tipo == 'Salida') {
                $enInventario -= $inventario->cantidad;
            }
        }


        // 2 - Realiza el ajuste de inventario
        $inventarioResultante = ($enInventario + $cantidadRegistrada->cantidad) - $request->cantidad;
        
        if( $inventarioResultante < 0 ) {
            return back()->with('error', 'No se puede ajustar la cantidad, no tiene suficiente inventario');
        }

        // 2.1 - Entrada de material con cantidad anterior, solo si se tomó material de esa ubicación
        // es decir, si la cantidad es mayor a 0
        if($cantidadRegistrada->cantidad > 0) {
            $movimiento = new MovimientosModel();
    
            $movimiento->proyecto = $cantidadInventario->first->proyecto->proyecto;
            $movimiento->cantidad = round($cantidadRegistrada->cantidad, 0);
            $movimiento->tipo = 'Entrada';
            $movimiento->comentario = 'Ajuste de requerimiento con folio: '.$cantidadRegistrada->folio_solicitud;
            $movimiento->fecha_registro = Carbon::now();
            $movimiento->id_parte = $cantidadInventario->first->proyecto->id_parte;
            $movimiento->numero_de_parte = $request->num_parte;
            $movimiento->ubicacion = $request->ubicacion;
            $movimiento->palet = $request->palet;
    
            $movimiento->save();
        }

        // 2.2 - Salida de material con nueva cantidad
        if($request->cantidad > 0) {
            $movimiento = new MovimientosModel();
    
            $movimiento->proyecto = $cantidadInventario->first->proyecto->proyecto;
            $movimiento->cantidad = $request->cantidad;
            $movimiento->tipo = 'Salida';
            $movimiento->comentario = 'Ajuste de requerimiento con folio: '.$cantidadRegistrada->folio_solicitud;
            $movimiento->fecha_registro = Carbon::now();
            $movimiento->id_parte = $cantidadInventario->first->proyecto->id_parte;
            $movimiento->numero_de_parte = $request->num_parte;
            $movimiento->ubicacion = $request->ubicacion;
            $movimiento->palet = $request->palet;
    
            $movimiento->save();
        }

        // 2.3 - Ajuste de cantidad en ubicación
        CantidadUbicacionesModel::where('id', $request->cantidad_id)->update(['cantidad' => $request->cantidad]);

        return back()->with('success', 'La cantidad fue ajustada exitosamente');
    }

    public function update(Request $request) {
        $status = ($request->action == '1') ? 'PREPARADO' : 'RECIBIDO';

        $solicitud = SolicitudesModel::where('id', $request->id)->update(['status' => $status]);

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

        $kit_nombre = BomsModel::select(
            'kit_nombre'
        )
        ->where('num_parte', $requerimientos->first->num_parte->num_parte)
        ->first();
        
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
}
