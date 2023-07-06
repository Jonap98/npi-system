<?php

namespace App\Models\externals\ekanban;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    use HasFactory;
    protected $connection = 'sqlsrv';
    protected $table = 'gen_users';
    protected $fillable = [
        'userGuid',
        'name',
        'username',
        'password',
        'roles',
    ];
}
