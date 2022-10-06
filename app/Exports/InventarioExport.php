<?php

namespace App\Exports;

use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InventarioExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'Folio',
            'Proyecto',
            'Número de parte',
            'Descripción',
            'Unidad de medida',
            'Inventario',
            'Ubicación',
            'Palet',
            'Fila'
            // 'Unidad de medida'
            // 'Comentario',
            // 'Fecha de registro',
        ];
    }

    public function collection()
    {
        $inventarios = DB::table('NPI_movimientos')
            ->join('NPI_partes', 'NPI_partes.id', '=', 'NPI_movimientos.id_parte')
            ->select(
                'NPI_movimientos.id',
                'NPI_movimientos.proyecto',
                'NPI_movimientos.cantidad',
                'NPI_movimientos.comentario',
                'NPI_movimientos.tipo',
                'NPI_movimientos.fecha_registro',
                'NPI_partes.id',
                'NPI_partes.numero_de_parte',
                'NPI_partes.descripcion',
                'NPI_partes.um',
                // 'NPI_partes.ubicacion',
                // 'NPI_partes.palet',
                'NPI_movimientos.ubicacion',
                'NPI_movimientos.palet',
                'NPI_movimientos.fila'
            )
            // ->orderBy('NPI_movimientos.fecha_registro', 'asc')
            ->where('active', 1)
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

        $final = array();
        
        // array_push($final, $temp_list[0]);


        for($i = 0; $i < count($temp_list); $i++) {
            array_push($final, 
                array(
                    'id' => $temp_list[$i]->id,
                    'proyecto' => $temp_list[$i]->proyecto,
                    'numero_de_parte' => $temp_list[$i]->numero_de_parte,
                    'descripcion' => $temp_list[$i]->descripcion,
                    'um' => $temp_list[$i]->um,
                    'cantidad' => $temp_list[$i]->cantidad,
                    'ubicacion' => $temp_list[$i]->ubicacion,
                    'palet' => $temp_list[$i]->palet,
                    'fila' => $temp_list[$i]->fila,
                )
            );
            
        }

        return collect($final);
    }
}
