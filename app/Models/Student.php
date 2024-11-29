<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Authenticatable
{
    use HasApiTokens, Notifiable;
    //
}
