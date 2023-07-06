<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InventarioExport;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientosModel;
use App\Models\PartesModel;

class InventarioExportController extends Controller
{
    public function crearExcel() {
        // 1- Obtiene todos los numeros de parte que tienen movimientos
        $inventarios = MovimientosModel::get()
            ->unique('numero_de_parte');

        foreach ($inventarios as $inventario) {
            // 2- Para cada número, se obtiene su ubicacion y palet sin repetir
            $ubicaciones = MovimientosModel::select('ubicacion')
            ->where('numero_de_parte', $inventario->numero_de_parte)
            ->get()
            ->unique('ubicacion');

            $ubicaciones_array = array();
            foreach ($ubicaciones as $ubicacion) {
                $palets = MovimientosModel::select('palet')
                ->where('numero_de_parte', $inventario->numero_de_parte)
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
            $inventario->ubicaciones_registradas = $ubicaciones_array;
        }

        foreach ($inventarios as $inventario) {
            // Para este número de parte se obtienen todos sus movimientos
            $cantidades = MovimientosModel::select(
                'numero_de_parte',
                'cantidad',
                'tipo',
                'ubicacion',
                'palet'
            )
            ->where('numero_de_parte', $inventario->numero_de_parte)
            ->get();

            // Se obtiene la descripción y unidad de medida
            $datos = PartesModel::select(
                'descripcion',
                'um'
            )
            ->where('numero_de_parte', $inventario->numero_de_parte)
            ->first();

            $inventario->descripcion = $datos->descripcion;
            $inventario->um = $datos->um;

            // Calculo de inventario
            $cantidad_inventario = 0;
            $ubicaciones_list = array();

            foreach ($cantidades as $cantidad) {
                // Se realiza la sumatoria validando si son entradas o salidas
                if(strtoupper($cantidad->tipo) == 'ENTRADA') {
                    $cantidad_inventario += $cantidad->cantidad;
                }
                if(strtoupper($cantidad->tipo) == 'SALIDA'){
                    $cantidad_inventario -= $cantidad->cantidad;
                }
            }

            $inventario->ubicaciones = $ubicaciones_list;
            $inventario->cantidad_inventario = $cantidad_inventario;
            $inventario->cantidades = $cantidades;
        }


        $listaOrdenada = [];
        foreach ($inventarios as $inventario) {
            $ubicaciones = '';
            foreach ($inventario->ubicaciones_registradas as $ubicacion) {
                $ubicaciones = $ubicaciones.' '.$ubicacion->ubicacion;
                foreach ($ubicacion->palets_registrados as $palets) {
                    $ubicaciones = $ubicaciones.' '.$palets->palet;
                }
            }
            $obj = ([
                'Folio' => $inventario->id,
                'Proyecto' => $inventario->proyecto,
                'Número  de parte'=> $inventario->numero_de_parte,
                'Descripción' => $inventario->descripcion,
                'Unidad  de medida'=> $inventario->um,
                'Inventario' => $inventario->cantidad_inventario,
                'Ubicación' => $ubicaciones,
            ]);

            array_push($listaOrdenada, $obj);
        }

        $export = new InventarioExport($listaOrdenada);

        return Excel::download($export, 'Inventario.xlsx');
    }

}
