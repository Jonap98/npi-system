<?php

namespace App\Models\test;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartesModel extends Model
{
    use HasFactory;
    protected $table = 'NPI_partes_test';
    protected $fillable = [
        'numero_de_parte',
        'descripcion',
        'um',
        'ubicacion',
        'palet',
        'proyecto',
    ];
}
