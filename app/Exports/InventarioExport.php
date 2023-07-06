<?php

namespace App\Exports;

use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Arr;
use App\Exports\InventarioExport;
use App\Models\MovimientosModel;
use App\Models\PartesModel;

class InventarioExport implements FromArray, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct(array $data) {
        $this->data = $data;
    }

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
        ];
    }

    public function array(): array {
        return $this->data;
    }
}
