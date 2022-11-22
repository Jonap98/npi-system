<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $connection = 'sqlsrv';
    protected $table = 'users';
    protected $fillable = [
        'name',
        'username',
        'email',
        'role',
        'password',
        'active'
    ];
    // protected $table = 'PFEP_users';
    // protected $fillable = [
    //     'REG',
    //     'user_name',
    //     'password',
    //     'name',
    //     'privilegio',
    // ];
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'role',
    //     'active',
    //     'password',
    // ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
