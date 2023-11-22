<?php

namespace App\Http\Controllers\requerimientos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BomsModel;
use App\Models\SolicitudesModel;
use App\Models\RequerimientosModel;
use Illuminate\Support\Facades\Auth;

class KitsController extends Controller
{
    public function index($kit) {
        $team = BomsModel::select(
            'status',
            'team'
        )
        ->where('num_parte', $kit)
        ->first();

        $kits = BomsModel::select(
            'id',
            'kit_nombre',
            'num_parte',
            'status',
            'team'
        )
        ->where('team', $team->team)
        ->where('status', 'like', 'KIT%')
        ->get();

        return view('requerimientos.porModelo.kits', array('kits' => $kits, 'modelo' => $team->status.$team->team));
    }

    public function show($id) {

        // Se obtienen los números a solicitar con el siguiente criterio:
        // Todo lo perteneciente al mismo modelo (TEAM), que en Status diga 'BUY' o 'MAKE'
        $team = BomsModel::select(
            'id',
            'kit_nombre',
            'num_parte',
            'status',
            'team'
        )
        ->where('id', $id)
        ->first();

        $kits = BomsModel::select(
            'id',
            'kit_nombre',
            'kit_descripcion',
            'cantidad',
            'num_parte',
            'status',
            'team',
            'ubicacion'
        )
        ->where('team', $team->team)
        ->where('kit_nombre', $team->kit_nombre)
        ->where('status', 'BUY')
        ->orWhere('status', 'like', 'MAKE%')
        ->orderBy('kit_descripcion', 'asc')
        ->get()
        ->where('kit_nombre', $team->kit_nombre)
        ->where('team', $team->team);

        $array_kits = [];
        foreach ($kits as $kit) {
            array_push($array_kits, $kit);
        }

        // $kit_padre = BomsModel::select(
        //     'id',
        //     'kit_nombre',
        //     'kit_descripcion',
        //     'cantidad',
        //     'num_parte',
        //     'status',
        //     'team',
        //     'ubicacion'
        // )
        // ->where('id', $id)
        // ->first();

        // array_push($array_kits, $kit_padre);

        return response([
            'data' => $array_kits
        ]);
    }

    public function store(Request $request) {

        // 1 - Se obtiene el último folio
        $ultimoFolio = SolicitudesModel::select(
            'id',
            'folio'
        )
        ->orderBy('id', 'desc')
        ->first();

        if(!$ultimoFolio) {
            $folio = 0;
        } else {
            $folio = $ultimoFolio->folio + 1;
        }

        // 2 - Se crea la solicitud con nuevo folio
        $solicitud = new SolicitudesModel();

        $solicitud->folio = $folio;
        $solicitud->solicitante = $request->solicitante;
        $solicitud->status = 'SOLICITADO';

        $solicitud->save();

        // 3 - Se obtienen los números a solicitar con el siguiente criterio:
        // Todo lo perteneciente al mismo modelo (TEAM), que en Status diga 'BUY' o 'MAKE'
        $team = BomsModel::select(
            'id',
            'kit_nombre',
            'num_parte',
            'status',
            'team'
        )
        ->where('id', $request->id)
        ->first();

        $kits = BomsModel::select(
            'id',
            'kit_nombre',
            'kit_descripcion',
            'cantidad',
            'num_parte',
            'status',
            'team',
            'ubicacion'
        )
        ->where('team', $team->team)
        ->where('kit_nombre', $team->kit_nombre)
        ->where('status', 'BUY')
        ->orWhere('status', 'like', 'MAKE%')
        ->get()
        ->where('kit_nombre', $team->kit_nombre)
        ->where('team', $team->team);


        // $kit_padre = BomsModel::select(
        //     'id',
        //     'kit_nombre',
        //     'kit_descripcion',
        //     'cantidad',
        //     'num_parte',
        //     'status',
        //     'team',
        //     'ubicacion'
        // )
        // ->where('id', $request->id)
        // ->first();

        // $requerimiento = new RequerimientosModel();

        // $requerimiento->folio = $folio;
        // $requerimiento->num_parte = $request->num_parte;
        // $requerimiento->kit_nombre = $kit_padre->kit_nombre;
        // $requerimiento->descripcion = $kit_padre->kit_descripcion;
        // $requerimiento->cantidad_requerida = ($request->cantidad * $kit_padre->cantidad);
        // // Validar si seguirá siendo necesario este campo en la BD
        // $requerimiento->cantidad_ubicacion = 1000;
        // $requerimiento->solicitante = $request->solicitante;
        // $requerimiento->comentario = '';
        // $requerimiento->status = 'SOLICITADO';
        // $requerimiento->ubicacion = $kit_padre->ubicacion ?? '';

        // $requerimiento->save();

        // Para cada uno de los registros obtenidos, se crea un requerimiento
        foreach ($kits as $kit) {
            $ubicacion = BomsModel::select(
                'num_parte',
                'ubicacion'
            )
            ->where('num_parte', $kit->num_parte)
            ->first();

            $requerimiento = new RequerimientosModel();

            $requerimiento->folio = $folio;
            $requerimiento->num_parte = $kit->num_parte;
            $requerimiento->kit_nombre = $kit->kit_nombre;
            $requerimiento->descripcion = $kit->kit_descripcion;
            $requerimiento->cantidad_requerida = ($request->cantidad * $kit->cantidad);
            // Validar si seguirá siendo necesario este campo en la BD
            $requerimiento->cantidad_ubicacion = 1000;
            $requerimiento->solicitante = Auth::user()->username;
            $requerimiento->comentario = '';
            $requerimiento->status = 'SOLICITADO';
            $requerimiento->ubicacion = $ubicacion->ubicacion;
            $requerimiento->team = $request->team;
            $requerimiento->active = 1;

            $requerimiento->save();
        }

        return back()->with('success', 'El requerimiento fue creado exitosamente');

    }

    public function generatePDF(Request $request) {
        return response([
            'data' => $request->all()
        ]);

        $arr = json_decode($request->kit, true);

        $fileName = "Vista previa.pdf";

        // Descargar archivo
        $pdf = \PDF::loadView('requerimientos.porModelo.make.pdf', array('requerimientos' => $arr['details']));

        return $pdf->download($fileName);

    }
}
