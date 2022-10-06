<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientosModel extends Model
{
    use HasFactory;
    protected $table = 'NPI_movimientos';
    protected $fillable = [ // Folio es el id
        'proyecto',
        'cantidad',
        'tipo',
        'comentario',
        'fecha_registro',
        'id_parte',
    ];
}
