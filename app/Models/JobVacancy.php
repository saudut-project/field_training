<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobVacancy extends Model
{
    protected $primaryKey = 'job_vacancy_id';
    protected $fillable = ['title', 'description', 'requirements', 'institution_id', 'status'];
}
