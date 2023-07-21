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
        'created_at',
        'updated_at',
        'ubicacion',
        'palet',
        'numero_de_parte',
        'numero_guia',
        'usuario',
    ];
}
