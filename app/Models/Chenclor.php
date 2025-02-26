<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Chenclor extends Authenticatable
{
    use HasApiTokens, Notifiable;
    protected $table = 'chenclor';
    protected $primaryKey = 'chenclor_id'; // Specify the primary key if not 'id'

    protected $fillable = ['username', 'password', 'email'];
}
