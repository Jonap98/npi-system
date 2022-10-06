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
        if(count($inventarios) > 0)
        array_push($temp_list, $inventarios[0]);

        $counter = $inventarios->count(); 
        
        for($i = 1; $i < $counter; $i++) {
                
            if($inventarios[$i]->numero_de_parte == $temp_list[count($temp_list)-1]->numero_de_parte) {
                if(strtoupper($inventarios[$i]->tipo) == 'ENTRADA') {
                    $temp_list[count($temp_list)-1]->cantidad += $inventarios[$i]->cantidad;
                } 
                if(strtoupper($inventarios[$i]->tipo) == 'SALIDA'){
                    $temp_list[count($temp_list)-1]->cantidad -= $inventarios[$i]->cantidad;

                    if($temp_list[count($temp_list)-1]->cantidad < 0) {
                        $temp_list[count($temp_list)-1]->cantidad = 0;
                    }
                }
            } else {
                array_push($temp_list, $inventarios[$i]);
            }
            
        }

        return view('inventario.inventario', array('inventarios' => $temp_list));
    }
    
    public function image($id) {
        return view('inventario.image');
    }

    public function export(){
        return Excel::download(new InventarioExport, 'inventario.xlsx');
    }
}
