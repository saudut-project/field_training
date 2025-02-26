<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingRequest extends Model
{
    protected $table = 'training_requests';
    protected $primaryKey = 'training_request_id';
    protected $fillable = ['status', 'approves', 'student_id', 'job_vacancy_id', 'college_id', 'department_id', 'institution_id','status_lists'];

    protected $casts = [
        'approves' => 'array',
        'status_lists' => 'array',
    ];

       // In TrainingRequest.php
   public function student()
   {
       return $this->belongsTo(Student::class, 'student_id', 'student_id');
   }

   public function college()
   {
       return $this->belongsTo(College::class, 'college_id', 'college_id');
   }

   public function department()
   {
       return $this->belongsTo(Department::class, 'department_id', 'department_id');
   }

   public function jobVacancy()
   {
       return $this->belongsTo(JobVacancy::class, 'job_vacancy_id', 'job_vacancy_id');
   }

   
}
