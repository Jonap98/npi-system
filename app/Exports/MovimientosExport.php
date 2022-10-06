<?php

namespace App\Exports;

use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MovimientosExport implements FromCollection,WithHeadings
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
            'Tipo',
            'Cantidad',
            'Comentario',
            'Fecha de registro',
            // 'Folio',
            // 'Proyecto',
            // 'Comentario',
            // 'Número de parte',
            // 'Descripción',
            // 'Unidad de medida',
            // 'Entrada',
            // 'Salida',
            // 'Fecha de registro',
            // 'Id parte',
        ];
    }
    
    public function collection()
    {
        // $movimientos = DB::table('NPI_movimientos as a')
        //     ->join('NPI_partes', 'NPI_partes.id', '=', 'a.id_parte')
        //     ->select('a.id', 'a.proyecto', 'a.comentario', 'NPI_partes.numero_de_parte as numero_parte', 'NPI_partes.descripcion', 'NPI_partes.um as unidad_de_medida',
        //         DB::raw("(select sum(e.cantidad) from NPI_movimientos e WHERE e.tipo='ENTRADA' and a.id_parte=e.id_parte GROUP BY id_parte)entrada"),
        //         DB::raw("(select sum(NPI_movimientos.cantidad) from NPI_movimientos WHERE NPI_movimientos.tipo='SALIDA' and a.id_parte=NPI_movimientos.id_parte GROUP BY id_parte) as salida"),  
        //         DB::raw("(SELECT TOP 1 c.fecha_registro FROM NPI_movimientos as c WHERE a.id_parte=c.id_parte) as fecha_registro"),
        //         DB::raw('(SELECT TOP 1 c.id_parte FROM NPI_movimientos as c WHERE a.id_parte=c.id_parte) as id_parte'),
                
        //     )->get();
        $movimientos = DB::table('NPI_movimientos as a')
            ->join('NPI_partes', 'NPI_partes.id', '=', 'a.id_parte')
            ->select('a.id', 'a.proyecto', 'NPI_partes.numero_de_parte as numero_parte', 'NPI_partes.descripcion', 'NPI_partes.um as unidad_de_medida', 'a.tipo', 'a.cantidad', 'a.comentario',  'a.fecha_registro', 
                // DB::raw("(select sum(e.cantidad) from NPI_movimientos e WHERE e.tipo='ENTRADA' and a.id_parte=e.id_parte GROUP BY id_parte)entrada"),
                // DB::raw("(select sum(NPI_movimientos.cantidad) from NPI_movimientos WHERE NPI_movimientos.tipo='SALIDA' and a.id_parte=NPI_movimientos.id_parte GROUP BY id_parte) as salida"),  
                // DB::raw("(SELECT TOP 1 c.fecha_registro FROM NPI_movimientos as c WHERE a.id_parte=c.id_parte) as fecha_registro"),
                // DB::raw('(SELECT TOP 1 c.id_parte FROM NPI_movimientos as c WHERE a.id_parte=c.id_parte) as id_parte'),
                
            )->get();

         return $movimientos;   

    }
}