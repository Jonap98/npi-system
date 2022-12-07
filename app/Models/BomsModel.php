<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BomsModel extends Model
{
    use HasFactory;
    protected $table = 'NPI_boms';
    protected $fillable = [
        'id',
        'kit_nombre',
        'nivel',
        'num_parte',
        'kit_descripcion',
        'um',
        'cantidad',
        'status',
        'requerido'
    ];
}
