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
            'Ubicación',
            'Palet',
            'Unidad de medida',
            'Tipo',
            'Cantidad',
            'Comentario',
            'Fecha de registro',
            'Número de guía',
            'Usuario',
        ];
    }

    public function collection()
    {
        $movimientos = DB::table('NPI_movimientos as movimiento')
            ->join('NPI_partes', 'NPI_partes.numero_de_parte', '=', 'movimiento.numero_de_parte')
            ->select(
                'movimiento.id',
                'movimiento.proyecto',
                'NPI_partes.numero_de_parte as numero_parte',
                'NPI_partes.descripcion',
                'movimiento.ubicacion',
                'movimiento.palet',
                'NPI_partes.um as unidad_de_medida',
                'movimiento.tipo',
                'movimiento.cantidad',
                'movimiento.comentario',
                'movimiento.fecha_registro',
                'movimiento.numero_guia',
                'movimiento.usuario'
            )->get();

        return $movimientos;

    }
}
