<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequerimientosModel;
use App\Models\SolicitudesModel;

class SolicitudRequerimientosController extends Controller
{
    public function index() {
        $requerimientos = SolicitudesModel::select(
            'id',
            'folio',
            'solicitante',
            'status',
            'created_at as fecha'
        )
        ->where('status', '!=', 'RECIBIDO')
        ->orderBy('id', 'desc')
        ->get();

        // return response([
        //     'data' => $requerimientos
        // ]);

        return view('requerimientos.solicitudes.index', array('requerimientos' => $requerimientos));
    }

    public function filter(Request $request) {
        // dd($request);
        // return response([
        //     'status' => $request->status
        // ]);
        $requerimientos = SolicitudesModel::select(
            'id',
            'folio',
            'solicitante',
            'status',
            'created_at as fecha'
        )
        ->where('status', $request->status)
        ->orderBy('id', 'desc')
        ->get();

        return view('requerimientos.solicitudes.index', array('requerimientos' => $requerimientos));
    }

    public function details($folio) {
        $requerimientos = RequerimientosModel::select(
            'id',
            'folio',
            'num_parte',
            'descripcion',
            'cantidad_requerida',
            'cantidad_ubicacion',
            'solicitante',
            'comentario',
            'status',
        )
        ->where('folio', $folio)
        ->get();

        return view('requerimientos.solicitudes.details', array('requerimientos' => $requerimientos, 'folio' => $folio));
    }

    public function update(Request $request) {
        // dd($request);
        $status = ($request->action == '1') ? 'PREPARADO' : 'RECIBIDO';

        $solicitud = SolicitudesModel::where('id', $request->id)->update(['status' => $status]);

        return back()->with('success', 'La solicitud fue actualizada exitosamente');
    }

    public function exportPDF(Request $request) {
        // dd($request);

        $requerimientos = RequerimientosModel::select(
            'id',
            'folio',
            'num_parte',
            'descripcion',
            'cantidad_requerida',
            'cantidad_ubicacion',
            'solicitante',
            'comentario',
            'status',
            'created_at',
        )
        ->where('folio', $request->folio)
        ->get();

        // return response([
        //     'data' => $requerimientos
        // ]);


        // Se debe decodificar la respuesta para hacerla compatible con el PDF
        // $data = json_decode($request->prod);
        // $count = count($data);
        $data = json_decode($requerimientos);
        $count = count($data);
        // return response([
        //     'count' => $count,
        //     'data' => $data,
        // ]);


        
        // Se vuelve a codificar para acceder a ella como arreglo
        $arr = json_decode(json_encode($data), true);
        
        // $fileName = "Requerimiento de material ".$arr[0]['TIPO']."-".substr($arr[0]['FECHAREAL'], 0, 10).".pdf";
        $fileName = "Requerimiento de material.pdf";
        
        // Descargar archivo
        $pdf = \PDF::loadView('requerimientos.solicitudes.pdf', array('requerimientos' => $data, 'count' => $count));
        
        return $pdf->download($fileName);
    }
}
