<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    protected $connection = 'sqlsrv';
    // protected $fillable = [
    //     'reg',
    //     'user_name',
    //     'password',
    //     'name',
    //     'privilegio',
    // ];

    // // protected $connection = 'sqlsrv';
    // // protected $table = 'PFEP_users';
    // // protected $fillable = [
    // //     'reg',
    // //     'user_name',
    // //     'name',
    // //     'privilegio',
    // // ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'users';
    protected $fillable = [
        'name',
        'username',
        'password',
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
