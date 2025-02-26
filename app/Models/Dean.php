<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Dean extends Authenticatable
{
    use HasApiTokens, Notifiable;
    protected $primaryKey = 'dean_id'; // Specify the primary key if not 'id'

    protected $fillable = ['username', 'password', 'email'];
}
