<?php

namespace App\Models\wramateriales;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class UsersModel extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    
    protected $connection = 'sqlsrv';
    protected $table = 'PFEP_users';
    protected $fillable = [
        'reg',
        'user_name',
        'name',
        'privilegio',
    ];
}
