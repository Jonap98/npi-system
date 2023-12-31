<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use App\Exports\InventarioExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\MovimientosModel;
use App\Models\PartesModel;

class InventarioController extends Controller
{
    public function index() {
        $inventarios = DB::table('NPI_movimientos')
        ->get()
        ->unique('numero_de_parte');

        foreach ($inventarios as $inventario) {

            // Proceso para obtener ubicacion y palet sin repetir
            $ubicaciones = DB::table('NPI_movimientos')
            ->select('ubicacion')
            ->where('numero_de_parte', $inventario->numero_de_parte)
            ->get()
            ->unique('ubicacion');

            $ubicaciones_array = array();
            foreach ($ubicaciones as $ubicacion) {
                $palets = DB::table('NPI_movimientos')
                ->select('palet')
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

        $inventarioFinal = [];
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

            $datos = PartesModel::select(
                'descripcion',
                'um'
            )
            ->where('numero_de_parte', $inventario->numero_de_parte)
            ->first();

            $inventario->descripcion = $datos->descripcion ?? '';
            $inventario->um = $datos->um ?? '';

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

            if( $cantidad_inventario > 0 )
                array_push($inventarioFinal, $inventario);
        }

        return view('inventario.inventario', array('inventarios' => $inventarioFinal));
    }

    public function image($id) {
        return view('inventario.image');
    }

    public function export(){
        return Excel::download(new InventarioExport, 'inventario.xlsx');
    }
}
