<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CantidadUbicacionesModel extends Model
{
    use HasFactory;
    protected $table = 'NPI_cantidad_ubicaciones';
    protected $fillable = [
        'id',
        'folio_solicitud',
        'num_parte',
        'cantidad',
        'ubicacion',
        'palet',
        'status',
        'id_requerimiento',
    ];
}
