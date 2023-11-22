<?php

namespace App\Http\Controllers\solicitudes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Models\RequerimientosModel;

class SolicitudDetallesController extends Controller
{
    public function delete(Request $request) {
        $registros = explode(',', $request->registros);

        RequerimientosModel::whereIn(
            'id', $registros
        )
        ->update([
            'active' => 0,
            'deleted_by' => Auth::user()->username ?? '',
            'deleted_at' => Carbon::now()
        ]);

        return back()->with('success', '¡Registros eliminados exitosamente!');
    }
}
