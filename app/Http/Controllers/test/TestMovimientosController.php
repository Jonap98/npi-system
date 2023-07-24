<?php

namespace App\Http\Controllers\test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Exports\MovimientosExport;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\test\MovimientosModel;
use App\Models\test\PartesModel;
use App\Models\UbicacionesModel;
use App\Models\InventarioModel;

class TestMovimientosController extends Controller
{
    public function index() {
        $movimientos = DB::table('NPI_movimientos_test as movimiento')
            ->join('NPI_partes_test as parte', 'parte.numero_de_parte', '=', 'movimiento.numero_de_parte')
            ->select(
                'movimiento.id',
                'movimiento.proyecto',
                'parte.numero_de_parte as numero_parte',
                'parte.descripcion',
                'movimiento.ubicacion',
                'movimiento.palet',
                'parte.um as unidad_de_medida',
                'movimiento.tipo',
                'movimiento.cantidad',
                'movimiento.comentario',
                'movimiento.fecha_registro',
                'movimiento.numero_guia',
                'movimiento.usuario'
            )->get();

        return view('test.movimientos.consulta', array('movimientos' => $movimientos));
    }

    public function create() {
        $movimiento = new MovimientosModel();

        $partes = PartesModel::get();
        $ubicaciones = UbicacionesModel::where('tipo', 'NPI')->get();

        foreach ($partes as $parte) {
            $parte->descripcion = str_replace('"', "''", $parte->descripcion);
        }

        return view('test.movimientos.movimientos', array('movimiento' => $movimiento, 'partes' => $partes, 'ubicaciones' => $ubicaciones));
    }

    public function store(Request $request) {


        $validatedData = $request->validate([
            'tipo' => 'required|in:Entrada,Salida,Ajuste',
        ]);

        $currentUser = Auth::user()->username;

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

                $movimiento->proyecto = $request->$proyecto;
                $movimiento->cantidad = $request->$cantidad;
                $movimiento->tipo = $request->tipo;
                $movimiento->comentario = $request->$comentario;
                $movimiento->fecha_registro = Carbon::now();
                $movimiento->id_parte = $request->$id_parte;
                $movimiento->numero_de_parte = $request->$numero_de_parte;
                $movimiento->ubicacion = $request->$ubicacion;
                $movimiento->palet = $request->$palet;
                $movimiento->usuario = $currentUser;

                $movimiento->save();
            }
        }

        return redirect('test/movimientos')->with('success', 'Registro creado exitosamente');

    }

    public function export(){
        return Excel::download(new MovimientosExport, 'movimientos.xlsx');
    }

    // Función de prueba para query con raw
    public function indextestGroupBy() {

        $movimientos = DB::table('NPI_movimientos_test as a')
            ->join('NPI_partes_test', 'NPI_partes_test.id', '=', 'a.id_parte')
            ->select('a.id', 'a.proyecto', 'a.comentario', 'NPI_partes_test.numero_de_parte as numero_parte', 'NPI_partes_test.descripcion', 'NPI_partes_test.um as unidad_de_medida',
                DB::raw('(select sum(e.cantidad) from NPI_movimientos_test e WHERE e.tipo="ENTRADA" and a.id_parte=e.id_parte GROUP BY id_parte)entrada'),
                DB::raw('(select sum(NPI_movimientos_test.cantidad) from NPI_movimientos_test WHERE NPI_movimientos_test.tipo="SALIDA" and a.id_parte=NPI_movimientos_test.id_parte GROUP BY id_parte) as salida'),
                DB::raw('(SELECT TOP 1 c.fecha_registro FROM NPI_movimientos_test as c WHERE a.id_parte=c.id_parte ) as fecha_registro'),
                DB::raw('(SELECT TOP 1 c.id_parte FROM NPI_movimientos_test as c WHERE a.id_parte=c.id_parte ) as id_parte'),

            )->get();

    }

    // Test nuevo inventario
    public function testInventario(Request $request) {
        $inventario = InventarioModel::select(
            'id',
            'numero_de_parte',
            'cantidad',
            'ubicacion',
            'palet',
        )
        ->where('numero_de_parte', $request->numero_de_parte)
        ->where('ubicacion', $request->ubicacion)
        ->where('palet', $request->palet)
        ->first();

        if( !$inventario ) {
            InventarioModel::create([
                'numero_de_parte' => $request->numero_de_parte,
                'cantidad' => $request->cantidad,
                'ubicacion' => $request->ubicacion,
                'palet' => $request->palet,
            ]);

            return response([
                'msg' => 'Movimiento insertado correctamente'
            ]);
        }

        if( $request->tipo == 'entrada' ) {
            InventarioModel::where( 'id', $inventario->id )
                ->update([ 'cantidad' => $inventario->cantidad += $request->cantidad ]);

            return response([
                'msg' => 'Registro de entrada actualizado exitosamente'
            ]);
        } else {
            if( $request->cantidad > $inventario->cantidad ) {
                return response([
                    'msg' => 'La cantidad solicitada excede el inventario'
                ]);
            } else {
                InventarioModel::where( 'id', $inventario->id )
                    ->update([ 'cantidad' => $inventario->cantidad -= $request->cantidad ]);

                return response([
                    'msg' => 'Registro de salida actualizado exitosamente'
                ]);
            }
        }

        return response([
            'msg' => 'Hay inventario en esta ubicación',
            'data' => $inventario
        ]);





    }
}
