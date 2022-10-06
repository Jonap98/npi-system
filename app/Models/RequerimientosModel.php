<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequerimientosModel extends Model
{
    use HasFactory;
    protected $table = 'requerimientos';
    protected $fillable = [
        'folio',
        'proyecto',
        'numero_de_parte',
        'descripcion',
        'um',
        'cantidad',
        'comentario',
        'fecha',
        'persona_que_reuiere',
        'status',
    ];
}
