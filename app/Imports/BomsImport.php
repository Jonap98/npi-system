<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\BomsModel;
use Carbon\Carbon;

class BomsImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    
    public function model(array $row) {

        return new BomsModel([
            'kit_nombre' => $row[0],
            'nivel' => $row[1],
            'num_parte' => $row[2],
            'kit_descripcion' => $row[3],
            'um' => $row[4],
            'cantidad' => $row[5],
            'status' => $row[6],
            'requerido' => $row[7] ?? 0
        ]);

    }
}