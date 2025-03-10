<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Chairperson extends Authenticatable
{
    use HasApiTokens, Notifiable;
    protected $primaryKey = 'chairperson_id'; // Specify the primary key if not 'id'
    protected $hidden = ['password', 'remember_token'];
    protected $table = 'chairpersons';
    protected $fillable = ['username', 'password', 'email'];
}
