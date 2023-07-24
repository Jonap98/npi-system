<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventarioModel extends Model
{
    use HasFactory;
    protected $table = 'NPI_inventario';
    protected $fillable = [
        'numero_de_parte',
        'cantidad',
        'ubicacion',
        'palet',
        'created_at',
        'updated_at',
    ];
}
