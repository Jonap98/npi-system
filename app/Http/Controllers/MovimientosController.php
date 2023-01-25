<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MovimientosModel;
use App\Models\PartesModel;
use App\Models\UbicacionesModel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Exports\MovimientosExport;
use Maatwebsite\Excel\Facades\Excel;

class MovimientosController extends Controller
{
    public function index() {
        $movimientos = DB::table('NPI_movimientos as a')
            ->join('NPI_partes', 'NPI_partes.id', '=', 'a.id_parte')
            ->select('a.id', 'a.proyecto', 'NPI_partes.numero_de_parte as numero_parte', 'NPI_partes.descripcion', 'a.ubicacion', 'a.palet', 'a.fila', 'NPI_partes.um as unidad_de_medida', 'a.tipo', 'a.cantidad', 'a.comentario',  'a.fecha_registro',
            )->get();

        return view('consulta', array('movimientos' => $movimientos));
    }

    public function create() {
        $movimiento = new MovimientosModel();

        $partes = PartesModel::get();
        $ubicaciones = UbicacionesModel::get();
        foreach ($partes as $parte) {
            $parte->descripcion = str_replace('"', "''", $parte->descripcion);
        }

        return view('/movimientos', array('movimiento' => $movimiento, 'partes' => $partes, 'ubicaciones' => $ubicaciones));
    }

    public function store(Request $request) {
        $validatedData = $request->validate([
            'tipo' => 'required|in:Entrada,Salida,Ajuste',
        ]);

        for($i = 0; $i < $request->counter; $i++) {
            
            $lastIndex = MovimientosModel::select('id')->orderBy('id', 'desc')->first();

            $movimiento = new MovimientosModel();
            $proyecto = 'proyecto'.$i;
            $cantidad = 'cantidad'.$i;
            $comentario = 'comentario'.$i;
            $id_parte = 'id_parte'.$i;
            $numero_de_parte = 'numero_de_parte'.$i;
            $ubicacion = 'ubicacion'.$i;
            $palet = 'palet'.$i;

            if($request->$proyecto) {

            $movimiento->proyecto = $request->$proyecto; // proyecto 02
            $movimiento->cantidad = $request->$cantidad; // 25
            $movimiento->tipo = $request->tipo; // Entrada
            $movimiento->comentario = $request->$comentario; // 25
            $movimiento->fecha_registro = Carbon::now();
            $movimiento->id_parte = $request->$id_parte; // 258
            $movimiento->numero_de_parte = $request->$numero_de_parte; // 258
            $movimiento->ubicacion = $request->$ubicacion;
            $movimiento->palet = $request->$palet;
            
            $movimiento->save();
            }
        }
        
        return redirect('movimientos')->with('success', 'Registro creado exitosamente');
        
    }

    public function export(){
        return Excel::download(new MovimientosExport, 'movimientos.xlsx');
    }

    // Función de prueba para query con raw
    public function indextestGroupBy() {
        
        $movimientos = DB::table('NPI_movimientos as a')
            ->join('NPI_partes', 'NPI_partes.id', '=', 'a.id_parte')
            ->select('a.id', 'a.proyecto', 'a.comentario', 'NPI_partes.numero_de_parte as numero_parte', 'NPI_partes.descripcion', 'NPI_partes.um as unidad_de_medida',
                DB::raw('(select sum(e.cantidad) from NPI_movimientos e WHERE e.tipo="ENTRADA" and a.id_parte=e.id_parte GROUP BY id_parte)entrada'),
                DB::raw('(select sum(NPI_movimientos.cantidad) from NPI_movimientos WHERE NPI_movimientos.tipo="SALIDA" and a.id_parte=NPI_movimientos.id_parte GROUP BY id_parte) as salida'),  
                DB::raw('(SELECT TOP 1 c.fecha_registro FROM NPI_movimientos as c WHERE a.id_parte=c.id_parte ) as fecha_registro'),
                DB::raw('(SELECT TOP 1 c.id_parte FROM NPI_movimientos as c WHERE a.id_parte=c.id_parte ) as id_parte'),
                
            )->get();
            
            return response([
                'result' => true,
                'data' => $movimientos,
            ]);
    }
}
