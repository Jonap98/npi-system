<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use App\Exports\InventarioExport;
use Maatwebsite\Excel\Facades\Excel;

class InventarioController extends Controller
{
    public function index() {
        $inventarios = DB::table('NPI_movimientos')
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
            // ->orderBy('NPI_movimientos.fecha_registro', 'asc')
            ->orderBy('NPI_movimientos.id_parte', 'desc')
            ->orderBy('NPI_movimientos.tipo', 'asc')
            ->get();

        $temp_list = array();
        if(count($inventarios) > 0) {
            array_push($temp_list, $inventarios[0]);
            $ubicaciones = DB::table('NPI_movimientos')
                ->select(
                    'tipo',
                    'ubicacion',
                    'palet',
                )
                ->where('id_parte', $inventarios[0]->id)
                ->get();
            $temp_list[0]->ubicaciones = $ubicaciones;
        }

        
        for($i = 1; $i < count($inventarios); $i++) {
            $repetido = false;
            $locations = [];
            for($j = 0; $j < count($temp_list); $j++) {
                
                if($inventarios[$i]->numero_de_parte == $temp_list[$j]->numero_de_parte) {
                    if($inventarios[$i]->ubicacion == $temp_list[$j]->ubicacion) {
                        $repetido = true;
                        if($inventarios[$i]->palet == $temp_list[$j]->palet) {
                            $ubicaciones = DB::table('NPI_movimientos')
                                ->select(
                                    'ubicacion',
                                    'palet',
                                )
                                ->where('id_parte', $inventarios[$i]->id)
                                ->get();

                                $temp_list[$j]->ubicaciones = $ubicaciones;
                        }

                        // $temp_list[count($temp_list)-1]->ubicaciones = $ubicaciones;


                        if(strtoupper($inventarios[$i]->tipo) == 'ENTRADA') {
                            $temp_list[$j]->cantidad += $inventarios[$i]->cantidad;
                        }
                        if(strtoupper($inventarios[$i]->tipo) == 'SALIDA'){
                            $temp_list[$j]->cantidad -= $inventarios[$i]->cantidad;
        
                            if($temp_list[$j]->cantidad < 0) {
                                $temp_list[$j]->cantidad = 0;
                            }
                        }

                        

                    }
                }
            }
            if(!$repetido) {
                array_push($temp_list, $inventarios[$i]);
                // $temp_list[count($temp_list)-1]->ubicaciones = 'JONA';
                $ubicaciones = DB::table('NPI_movimientos')
                ->select(
                    'ubicacion',
                    'palet',
                )
                ->where('id_parte', $inventarios[$i]->id)
                ->get();

                $temp_list[count($temp_list)-1]->ubicaciones = $ubicaciones;
            }

            foreach ($temp_list as $temp) {
                // $ubicaciones = DB::table('NPI_movimientos')
                //             ->select(
                //                 // 'tipo',
                //                 // 'ubicacion',
                //                 'palet',
                //             )
                //             ->where('id_parte', $temp->id)
                //             ->first();

                        // $temp->ubicaciones = $ubicaciones;
                        // return response([
                        //     'num' => $temp->numero_de_parte,
                        //     'data' => $ubicaciones
                        // ]);
            }

            // return response([
            //     'data' => $temp_list
            // ]);
                
        }

        // return response(['ubicaciones' => $ubicaciones, 'data' => $temp_list]);

        return view('inventario.inventario', array('inventarios' => $temp_list));
    }
    
    public function image($id) {
        return view('inventario.image');
    }

    public function export(){
        return Excel::download(new InventarioExport, 'inventario.xlsx');
    }
}
