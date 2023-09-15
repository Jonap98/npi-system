<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InventarioExport;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientosModel;
use App\Models\PartesModel;
use App\Models\InventarioModel;

class InventarioExportController extends Controller
{
    public function crearExcel() {
        $inventario = DB::table('NPI_movimientos')
        ->get()
        ->unique('numero_de_parte');

        $ubicaciones_con_inventario = [];

        foreach ($inventario as $num_parte) {
            $info = PartesModel::select(
                'descripcion',
                'proyecto',
                'um',
            )
            ->where('numero_de_parte', $num_parte->numero_de_parte)
            ->first();

            // Obtiene las cantidades por ubicación
            $cantidad_ubicaciones = InventarioModel::select(
                'cantidad',
                'ubicacion',
                'palet',
            )
            ->where('numero_de_parte', $num_parte->numero_de_parte)
            ->get();

            $ubicaciones = [];
            $suma_ubicaciones = 0;
            foreach ($cantidad_ubicaciones as $cantidad) {
                $suma_ubicaciones += $cantidad->cantidad;

                // Ingresa a la lista de ubicaciones, solo aquellas que tienen stock
                if($cantidad->cantidad > 0) {
                    array_push($ubicaciones, $cantidad);
                }
            }

            $num_parte->cantidad_total = $suma_ubicaciones;
            $num_parte->ubicaciones_registradas = $ubicaciones;
            $num_parte->descripcion = $info->descripcion ?? '';
            $num_parte->proyecto = $info->proyecto ?? '';
            $num_parte->um = $info->um ?? '';

            if($num_parte->cantidad_total > 0) {
                array_push($ubicaciones_con_inventario, $num_parte);
            }
        }

        $listaOrdenada = [];
        foreach ($ubicaciones_con_inventario as $inventario) {
            $ubicaciones = '';
            foreach ($inventario->ubicaciones_registradas as $ubicacion) {
                $ubicaciones = $ubicaciones.' '.$ubicacion->ubicacion.' '.$ubicacion->palet.': '.round($ubicacion->cantidad, 0).',';
                // foreach ($ubicacion->palets_registrados as $palets) {
                //     $ubicaciones = $ubicaciones.' '.$palets->palet;
                // }
            }
            $obj = ([
                'Folio' => $inventario->id,
                'Proyecto' => $inventario->proyecto,
                'Número  de parte'=> $inventario->numero_de_parte,
                'Descripción' => $inventario->descripcion,
                'Unidad  de medida'=> $inventario->um,
                'Inventario' => $inventario->cantidad_total,
                'Ubicación' => $ubicaciones,
            ]);

            array_push($listaOrdenada, $obj);
        }

        $export = new InventarioExport($listaOrdenada);

        return Excel::download($export, 'Inventario.xlsx');
    }

}
