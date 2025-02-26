<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Representative extends Authenticatable
{
    use HasApiTokens, Notifiable;
    protected $primaryKey = 'representative_id'; // Specify the primary key if not 'id'

    protected $fillable = ['username', 'password', 'email'];

    public function institutions()
    {
        return $this->hasMany(Institution::class, 'representative_id', 'representative_id');
    }

    
}
