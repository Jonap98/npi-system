<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequerimientosModel extends Model
{
    use HasFactory;
    protected $table = 'NPI_requerimientos';
    protected $fillable = [
        'id',
        'folio',
        'num_parte',
        'kit_nombre',
        'descripcion',
        'cantidad_requerida',
        'cantidad_ubicacion',
        'solicitante',
        'comentario',
        'status',
        'ubicacion',
        'team'
    ];
}
