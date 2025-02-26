<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DeanController;
use App\Http\Controllers\RepresentativeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\JobVacancyController;
use App\Http\Controllers\CollegeController;
use App\Http\Controllers\ChairpersonController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TrainingRequestController;
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Login 
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// تخزين كلية جديدة
Route::post('/colleges/store', [CollegeController::class, 'store']);


Route::get('/colleges', [CollegeController::class, 'index']);


Route::get('/college/showing/{id}', [CollegeController::class, 'show']);


Route::post('/college/update/setting/{id}', [CollegeController::class, 'update']);


Route::get('departments', [DepartmentController::class,'index']);

Route::post('departments/store', [DepartmentController::class,'store']);


Route::get('/departments/show/{id}', [DepartmentController::class,'show']);

Route::post('/departments/update/{id}', [DepartmentController::class,'update']);

Route::delete('/department/delete/{id}', [DepartmentController::class,'destroy']);

Route::get('/institutions/all', [InstitutionController::class,'index']);

Route::post('/institutions/store', [InstitutionController::class,'store']);

Route::get('/institutions/show/{id}', [InstitutionController::class,'showInstitution']);

Route::post('/institutions/update/{id}', [InstitutionController::class,'updateInstitution']);

Route::delete('/institutions/delete/{id}', [InstitutionController::class,'destroy']);

Route::get('/deans/all', [DeanController::class,'index']);
Route::get('/chencelor/all', [DeanController::class,'indexchencelor']);

Route::post('/deans/store', [DeanController::class,'store']);
Route::post('/chencelor/store', [DeanController::class,'storeChencelor']);
Route::post('/deans/update/{id}', [DeanController::class,'update']);
Route::get('/dean/show/{id}', [DeanController::class,'show']);
Route::get('/chencelor/show/{id}', [DeanController::class,'showchencelor']);
Route::get('/chairpersons/all', [ChairpersonController::class,'index']);
Route::post('/chairpersons/store', [ChairpersonController::class,'store']);
Route::get('/chairpersons/show/{id}', [ChairpersonController::class,'show']);
Route::post('/chairpersons/update/{id}', [ChairpersonController::class,'update']);
Route::post('students/register', [StudentController::class, 'register']);
Route::get('/representatives/all', [RepresentativeController::class,'index']);
Route::post('insertInstitution',[InstitutionController::class,'storeInstitution']);
Route::post('/trainingRequests/store', [TrainingRequestController::class,'store'])->middleware('auth:sanctum');
Route::get('/trainingRequests/myRequests', [TrainingRequestController::class,'fetchMyRequests'])->middleware('auth:sanctum');
Route::get('/trainingRequests/view/{id}', [TrainingRequestController::class,'viewRequest']);
Route::get('/trainingRequests/chairpersonRequests', [TrainingRequestController::class,'fetchRequestsByChairperson'])->middleware('auth:sanctum');
Route::get('/trainingRequests/deanRequests', [TrainingRequestController::class,'fetchRequestsByDean'])->middleware('auth:sanctum');
Route::get('/trainingRequests/all', [TrainingRequestController::class,'fetchRequestsByChencelor']);
Route::get('/trainingRequests/institutionRequests', [TrainingRequestController::class,'fetchRequestsByInstitution'])->middleware('auth:sanctum');
Route::get('/institutions/myInstitutions', [InstitutionController::class,'myInstitutions'])->middleware('auth:sanctum');
Route::post('/institutions/updateMyInstitution', [InstitutionController::class,'updateMyInstitution'])->middleware('auth:sanctum');
Route::get('/jobVacancies/all', [JobVacancyController::class,'index']);
Route::get('/jobVacancies/fetching/new', [JobVacancyController::class,'status']);
Route::post('training-requests/{id}/approve', [TrainingRequestController::class, 'approve'])->middleware('auth:sanctum');
Route::post('training-requests/{id}/submit', [TrainingRequestController::class, 'submit']);
Route::post('/jobVacancies/store', [JobVacancyController::class,'store'])->middleware('auth:sanctum');
Route::get('/jobVacancies/myJobVacancies', [JobVacancyController::class,'myJobVacancies'])->middleware('auth:sanctum');
Route::get('/jobVacancies/show/{id}', [JobVacancyController::class,'show']);
Route::post('/jobVacancies/update/{id}', [JobVacancyController::class,'update']);
Route::post('/jobVacancies/update-status/{id}', [JobVacancyController::class,'updateStatus'])->middleware('auth:sanctum');
Route::post('/chairpersons/update/my/password', [ChairpersonController::class, 'updateMyPassword'])->middleware('auth:sanctum');
Route::post('/representatives/update/my/password', [RepresentativeController::class, 'updateMyPassword'])->middleware('auth:sanctum');
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/admins/{id}', [AdminController::class, 'update']);
    Route::post('/deans/{id}', [DeanController::class, 'update']);
    Route::post('/chairpersons/{id}', [ChairpersonController::class, 'update']);
    Route::get('/deans/fetch/myData', [DeanController::class, 'myData']);
    Route::get('/chairpersons/fetch/myData', [ChairpersonController::class, 'myData']);
    Route::get('/representatives/fetch/myData', [InstitutionController::class, 'myData']);
    Route::post('/representatives/{id}', [RepresentativeController::class, 'update']);

    Route::apiResource('admins', AdminController::class);
    Route::apiResource('institutions', InstitutionController::class);
});
