<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class College extends Model
{
    protected $primaryKey = 'college_id';
    protected $table = 'colleges';
    protected $fillable = ['name','dean_id'];
    //
}
