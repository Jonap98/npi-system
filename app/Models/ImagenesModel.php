<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagenesModel extends Model
{
    use HasFactory;
    protected $table = 'NPI_imagenes';
    protected $fillable = [
        'numero_de_parte',
        'imagen'
    ];
}
