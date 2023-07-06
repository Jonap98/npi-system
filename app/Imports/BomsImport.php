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

        $types = ['PHANTOM', 'BUY REF', 'PDTE', ''];


        if(!in_array($row[6], $types)) {
            $registro = new BomsModel([
                'kit_nombre' => $row[0] ?? '', // Validar PTDE
                'nivel' => $row[1] ?? 0,
                'num_parte' => $row[2],
                'kit_descripcion' => $row[3],
                'um' => $row[4] ?? '',
                'cantidad' => $row[5],
                'status' => $row[6] ?? '',
                'ubicacion' => $row[7] ?? '',
                'team' => $row[8] ?? '',
                'requerido' => 1,
                // 'padre' => $row[9] ?? ''
            ]);

            $registro->save();

        }


    }
}
