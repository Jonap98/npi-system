<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MovimientosModel;
use App\Models\PartesModel;
use App\Models\UbicacionesModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Exports\MovimientosExport;
use Maatwebsite\Excel\Facades\Excel;

class MovimientosController extends Controller
{
    public function index() {
        $movimientos = DB::table('NPI_movimientos as movimiento')
            ->join('NPI_partes', 'NPI_partes.numero_de_parte', '=', 'movimiento.numero_de_parte')
            ->select(
                'movimiento.id',
                'movimiento.proyecto',
                'NPI_partes.numero_de_parte as numero_parte',
                'NPI_partes.descripcion',
                'movimiento.ubicacion',
                'movimiento.palet',
                'movimiento.fila',
                'NPI_partes.um as unidad_de_medida',
                'movimiento.tipo',
                'movimiento.cantidad',
                'movimiento.comentario',
                'movimiento.fecha_registro',
                'movimiento.numero_guia',
                'movimiento.usuario'
            )->get();

        return view('movimientos.consulta', array('movimientos' => $movimientos));
    }

    public function create() {
        $movimiento = new MovimientosModel();

        $partes = PartesModel::get();
        $ubicaciones = UbicacionesModel::where('tipo', 'NPI')->get();

        foreach ($partes as $parte) {
            $parte->descripcion = str_replace('"', "''", $parte->descripcion);
        }

        return view('movimientos.movimientos', array('movimiento' => $movimiento, 'partes' => $partes, 'ubicaciones' => $ubicaciones));
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
            $numero_guia = 'numero_guia'.$i;

            if($request->$proyecto) {

                $movimiento->proyecto = $request->$proyecto;
                $movimiento->cantidad = $request->$cantidad;
                $movimiento->tipo = $request->tipo;
                $movimiento->comentario = $request->$comentario;
                $movimiento->fecha_registro = Carbon::now()->subHours(1);
                $movimiento->id_parte = $request->$id_parte;
                $movimiento->numero_de_parte = $request->$numero_de_parte;
                $movimiento->ubicacion = $request->$ubicacion;
                $movimiento->palet = $request->$palet;
                $movimiento->numero_guia = $request->$numero_guia;
                $movimiento->created_at = Carbon::now()->subHours(1);
                $movimiento->updated_at = Carbon::now()->subHours(1);
                $movimiento->usuario = $currentUser;

                $movimiento->save();
            }
        }

        return redirect('movimientos')->with('success', 'Registro creado exitosamente');

    }

    public function export(){
        return Excel::download(new MovimientosExport, 'movimientos.xlsx');
    }
}
