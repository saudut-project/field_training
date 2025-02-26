<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['name', 'college_id', 'chairperson_id'];
    protected $primaryKey = 'department_id'; // Specify the primary key if not 'id'
}
