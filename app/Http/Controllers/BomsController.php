<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Imports\BomsImport;
use App\Models\BomsModel;

class BomsController extends Controller
{
    public function index() {
        $boms = BomsModel::select(
            'id',
            'kit_nombre',
            'nivel',
            'num_parte',
            'kit_descripcion',
            'um',
            'status',
            'requerido',
            'cantidad',
            'ubicacion',
            'team'
        )
        ->orderBy('id', 'asc')
        ->get();

        return view('boms.index', array('boms' => $boms));
    }

    public function import(Request $request) {
        set_time_limit(4800);
        $file = $request->file('import');

        if($file) {
            $bomList = BomsModel::select('id')->get();

            foreach ($bomList as $bom) {
                $bom->delete();
            }
        } else {
            return back()->with('error', 'No ha cargado ningún archivo');
        }

        Excel::import(new BomsImport, $file);

        return back()->with('success', 'La BOM fue cargada exitosamente');
    }

    public function edit(Request $request) {
        BomsModel::where(
            'id', $request->id
        )
        ->update([
            'num_parte' => $request->num_parte,
            'kit_nombre' => $request->kit_nombre,
            'kit_descripcion' => $request->kit_descripcion,
            'status' => $request->status,
            'ubicacion' => $request->ubicacion,
            'team' => $request->team,
        ]);

        return back()->with('success', 'El nombre del número de parte fue editado correctamente');
    }

    public function update(Request $request) {
        for($i = 0; $i < $request->counter; $i++) {
            $num = 'num_parte'.$i;

            $bom = BomsModel::select('id', 'requerido')->where('id', $request->$num)->first();
            $requerido = 1;
            if($bom->requerido == 1) {
                $requerido = 0;
            }

            BomsModel::where('id', $request->$num)->update(['requerido' => $requerido]);

        }

        return  back()->with('success', 'La BOM fue actualizada exitosamente');
    }
}
