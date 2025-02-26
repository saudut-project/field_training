<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Student extends Authenticatable
{
    use HasApiTokens, Notifiable;
        protected $primaryKey = 'student_id'; // Specify the primary key if not 'id'

    protected $fillable = ['username', 'password', 'email', 'college_id', 'department_id'];
}
