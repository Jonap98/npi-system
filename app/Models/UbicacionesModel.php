<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UbicacionesModel extends Model
{
    use HasFactory;
    protected $table = 'NPI_ubicaciones';
    protected $fillable = [
        'id',
        'ubicacion',
        'tipo'
    ];
}
