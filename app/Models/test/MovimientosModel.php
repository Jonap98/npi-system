<?php

namespace App\Models\test;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientosModel extends Model
{
    use HasFactory;
    protected $table = 'NPI_movimientos_test';
    protected $fillable = [
        'proyecto',
        'cantidad',
        'tipo',
        'comentario',
        'fecha_registro',
        'id_parte',
        'ubicacion',
        'numero_de_parte',
        'numero_guia',
    ];
}
