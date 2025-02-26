<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\College;
use App\Models\Department;
use App\Models\Institution;
use App\Models\Student;
use App\Models\JobVacancy;
use App\Models\TrainingRequest;
use Illuminate\Support\Facades\Log;

class TrainingRequestController extends Controller
{
    public function viewRequest($id)
    {
        $request = TrainingRequest::with(['student', 'college', 'department', 'jobVacancy'])->find($id);

        if (!$request) {
            return response()->json(['error' => 'Training request not found'], 404);
        }

        return response()->json([
            'training_request_id' => $request->training_request_id,
            'status' => $request->status,
            'approves' => $request->approves,
            'student_id' => $request->student_id,
            'job_vacancy_id' => $request->job_vacancy_id,
            'college_id' => $request->college_id,
            'department_id' => $request->department_id,
            'institution_id' => $request->institution_id,
            'created_at' => $request->created_at,
            'updated_at' => $request->updated_at,
            'student' => $request->student,
            'status_lists' => $request->status_lists,
            'college' => $request->college,
            'department' => $request->department,
            'job_vacancy' => $request->jobVacancy,
        ]);
    }

    public function fetchRequestsByChairperson()
    {
        $chairpersonUserId = auth()->user()->id; // Assuming the authenticated user is the chairperson
        $requests = TrainingRequest::with(['student', 'college', 'department', 'jobVacancy'])
            ->whereJsonContains('approves->chairperson->user_id', auth()->user()->chairperson_id)
            ->where('approves->chairperson->status', 'wait')
            ->get();

        return response()->json($requests);
    }

    public function fetchMyRequests()
    {
        $studentId = auth()->user()->student_id;
        $requests = TrainingRequest::with(['student', 'college', 'department', 'jobVacancy'])
            ->where('student_id', $studentId)
            ->get();

        return response()->json($requests);
    }

    public function store(Request $request)
    {
        Log::info($request->all());

        $validated = $request->validate([
            'job_vacancy_id' => 'required|exists:job_vacancies,job_vacancy_id',
        ]);

        $studentId = auth()->user()->student_id;

        // Check if the student is already enrolled in this job vacancy
        $existingRequest = TrainingRequest::where('student_id', $studentId)
            ->where('job_vacancy_id', $validated['job_vacancy_id'])
            ->first();

        if ($existingRequest) {
            return response()->json(['error' => 'يوجد لديك طلب سابق لهذا الفرصة التدريبية'], 400);
        }

        $jobVacancy = JobVacancy::where('job_vacancy_id', $validated['job_vacancy_id'])->first();
        $validated['institution_id'] = $jobVacancy->institution_id;

        Log::info($studentId);
        $student = Student::where('student_id', $studentId)->first();
        Log::info($student);

        $validated['department_id'] = $student->department_id;
        $validated['status'] = 'wait';
        $validated['college_id'] = $student->college_id;
        $validated['student_id'] = $studentId;
        $validated['approves'] = [
            'dean' => [
                'user_id' => College::where('college_id', $validated['college_id'])->first()->dean_id,
                'status' => 'wait'
            ],
            'chairperson' => [
                'user_id' => Department::where('department_id', $validated['department_id'])->first()->chairperson_id,
                'status' => 'wait'
            ],
            'institution' => [
                'user_id' => Institution::where('institution_id', $validated['institution_id'])->first()->representative_id,
                'status' => 'wait'
            ]
        ];

        // Initialize status_lists with the application entry
        $validated['status_lists'] = [[
            'status' => 'تم تقديم الطلب',
            'role' => 'student',
            'user_name' => auth()->user()->name,
            'note' => "تم تقديم الطلب  " . auth()->user()->name . " بتاريخ " . now(),
            'created_at' => now()
        ]];

        $trainingRequest = TrainingRequest::create($validated);

        return response()->json($trainingRequest, 201);
    }

    public function approve(Request $request, $id)
    {
        Log::info($request->all());
        $validated = $request->validate([
            'role' => 'required|string|in:dean,chairperson,institution',
        ]);

        $trainingRequest = TrainingRequest::find($id);

        if (!$trainingRequest) {
            return response()->json(['error' => 'Training request not found'], 404);
        }

        $role = $validated['role'];
        $userId = $role == 'institution' ? auth()->user()->representative_id : auth()->user()->{$role.'_id'};

        if ($trainingRequest->approves[$role]['user_id'] !== $userId) {
            return response()->json(['error' => 'Unauthorized action'], 403);
        }

        // Update the approval status
        $approves = $trainingRequest->approves;
        $approves[$role]['status'] = 'approved';
        $trainingRequest->approves = $approves;

        // Add note to status_lists for approval
        $statusLists = $trainingRequest->status_lists ?? [];
        $statusLists[] = [
            'status' => 'approved',
            'role' => $role,
            'user_name' => auth()->user()->name,
            'note' => "تمت الموافقة من قبل " . auth()->user()->name . " ({$role}) بتاريخ " . now(),
            'created_at' => now()
        ];
        $trainingRequest->status_lists = $statusLists;

        $trainingRequest->save();

        // Check overall approval status
        $allApproved = true;
        $anyRejected = false;

        foreach ($approves as $approval) {
            if ($approval['status'] === 'rejected') {
                $anyRejected = true;
                break;
            }
            if ($approval['status'] !== 'approved') {
                $allApproved = false;
            }
        }

        if ($anyRejected) {
            $trainingRequest->status = 'rejected';
        } elseif ($allApproved) {
            $trainingRequest->status = 'approved';
        }

        $trainingRequest->save();

        return response()->json(['message' => 'Training request approved successfully']);
    }

    public function reject(Request $request, $id)
    {
        Log::info($request->all());
        $validated = $request->validate([
            'role' => 'required|string|in:dean,chairperson,institution',
            'reason' => 'required|string'
        ]);

        $trainingRequest = TrainingRequest::find($id);

        if (!$trainingRequest) {
            return response()->json(['error' => 'Training request not found'], 404);
        }

        $role = $validated['role'];
        $userId = $role == 'institution' ? auth()->user()->representative_id : auth()->user()->{$role.'_id'};

        if ($trainingRequest->approves[$role]['user_id'] !== $userId) {
            return response()->json(['error' => 'Unauthorized action'], 403);
        }

        // Update the approval status
        $approves = $trainingRequest->approves;
        $approves[$role]['status'] = 'rejected';
        $trainingRequest->approves = $approves;

        // Add note to status_lists for rejection
        $statusLists = $trainingRequest->status_lists ?? [];
        $statusLists[] = [
            'status' => 'rejected',
            'role' => $role,
            'user_name' => auth()->user()->name,
            'note' => "تمت الرفض من قبل " . auth()->user()->name . " ({$role}) بتاريخ " . now(),
            'created_at' => now()
        ];
        $trainingRequest->status_lists = $statusLists;

        // Update the overall status to rejected
        $trainingRequest->status = 'rejected';
        $trainingRequest->save();

        return response()->json($trainingRequest);
    }

    public function fetchRequestsByDean()
    {
        $deanUserId = auth()->user()->dean_id;
        $requests = TrainingRequest::with(['student', 'college', 'department', 'jobVacancy'])
            ->whereJsonContains('approves->dean->user_id', auth()->user()->dean_id)
            ->where('approves->dean->status', 'wait')
            ->get();

        return response()->json($requests);
    }
    public function fetchRequestsByChencelor()
    {
        $requests = TrainingRequest::with(['student', 'college', 'department', 'jobVacancy'])->get();

        return response()->json($requests);
    }
    public function fetchRequestsByInstitution()
    {
        $deanUserId = auth()->user()->dean_id;
        $requests = TrainingRequest::with(['student', 'college', 'department', 'jobVacancy'])
            ->whereJsonContains('approves->institution->user_id', auth()->user()->representative_id)
            ->where('approves->institution->status', 'wait')
            ->get();

        return response()->json($requests);
    }

    public function submit($id)
    {
        $trainingRequest = TrainingRequest::find($id);

        if (!$trainingRequest) {
            return response()->json(['error' => 'Training request not found'], 404);
        }

        // Check if the request is already submitted
        if ($trainingRequest->status === 'submitted') {
            return response()->json(['error' => 'Training request is already submitted'], 400);
        }

        // Update the status to submitted
        $trainingRequest->status = 'submitted';
        $trainingRequest->save();

        return response()->json(['message' => 'Training request submitted successfully']);
    }
}
