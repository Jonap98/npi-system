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
        'descripcion',
        'cantidad_requerida',
        'cantidad_ubicacion',
        'solicitante',
        'comentario',
        'status',
        

        // 'folio',
        // 'proyecto',
        // 'numero_de_parte',
        // 'descripcion',
        // 'um',
        // 'cantidad',
        // 'comentario',
        // 'fecha',
        // 'persona_que_reuiere',
        // 'status',
    ];
}
