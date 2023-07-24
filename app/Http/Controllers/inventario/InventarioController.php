<?php

namespace App\Http\Controllers\inventario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\MovimientosModel;

use App\Models\InventarioModel;

class InventarioController extends Controller
{
    public function index() {
        // $movimientos =
        $numeros_de_parte = DB::table('NPI_movimientos')
        ->get()
        ->unique('numero_de_parte');

        $inventario_por_ubicacion = [];

        foreach ($numeros_de_parte as $inventario) {

            $sumatoria_por_ubicacion = 0;

            $ubicaciones = MovimientosModel::select(
                'id',
                'numero_de_parte',
                'cantidad',
                'tipo',
                'ubicacion',
                'palet',
            )
            ->where('numero_de_parte', $inventario->numero_de_parte)
            // ->where('ubicacion', $inventario->ubicacion)
            // ->where('palet', $inventario->palet)
            ->get();

            // if($inventario->numero_de_parte == 'W11564632') {
            //     return response([
            //         'data' => $ubicaciones
            //     ]);

            // }

            foreach ($ubicaciones as $ubicacion) {

                if( $ubicacion->tipo == 'Entrada' ) {
                    $sumatoria_por_ubicacion += $ubicacion->cantidad;
                } else {
                    $sumatoria_por_ubicacion -= $ubicacion->cantidad;
                }
            }
            array_push($inventario_por_ubicacion, ([
                'id' => $ubicaciones[0]->id,
                'numero_de_parte' => $ubicaciones[0]->numero_de_parte,
                'cantidad' => $sumatoria_por_ubicacion,
                // 'tipo' => $ubicaciones[0]->tipo,
                'ubicacion' => $ubicaciones[0]->ubicacion,
                'palet' => $ubicaciones[0]->palet,
            ]));

            // return response([
            //     'data' => $inventario,
            //     'cantidad_en_ubicacion' => $sumatoria_por_ubicacion,
            //     'ubicaciones' => $ubicaciones
            // ]);
        }

        return response([
            'inventario' => $inventario_por_ubicacion,
            'data' => $numeros_de_parte
        ]);
    }
    // public function index() {

    //     $inventario = InventarioModel::select(
    //         'id',
    //         'numero_de_parte',
    //         'cantidad',
    //         'ubicacion',
    //         'palet',
    //     )
    //     ->get();


    //     return response([
    //         'data' => $inventario
    //     ]);
    // }
}
