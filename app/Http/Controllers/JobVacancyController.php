<?php

namespace App\Http\Controllers;

use App\Models\JobVacancy;
use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class JobVacancyController extends Controller
{
    public function index()
    {
        $jobVacancies = JobVacancy::where('status','=',1)->get();
        return response()->json($jobVacancies, 200);
    }

    public function status()
    {
        $jobVacancies = JobVacancy::where('status','=',0)->get();
        return response()->json($jobVacancies, 200);
    }

    public function myJobVacancies()
    {
        $institution = Institution::where('representative_id', auth()->user()->representative_id)->first();
        return JobVacancy::where('institution_id', $institution->institution_id)->get();
    }
    public function store(Request $request)
    {
        Log::info($request->all());
        $institution = Institution::where('representative_id', auth()->user()->representative_id)->first();

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
        ]);

        $validatedData['institution_id'] = $institution->institution_id;
        $validatedData['status'] = 0;

        $jobVacancy = JobVacancy::create($validatedData);

        return response()->json($jobVacancy, 201);
    }

    public function show($id)
    {

        return JobVacancy::find($id);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'requirements' => 'sometimes|required|string',
       //     'institution_id' => 'sometimes|required|exists:institutions,institution_id',
      //      'status' => 'sometimes|required|string',
        ]);

        $jobVacancy = JobVacancy::find($id);
        $jobVacancy->update($validatedData);

        return response()->json($jobVacancy, 200);
    }

    public function destroy(JobVacancy $jobVacancy)
    {
        $jobVacancy->delete();

        return response()->json(null, 204);
    }

    public function updateStatus(Request $request, $id)
    {
        Log::info($request->all());
        $request->validate([
            'status' => 'required'  // adjust status options as needed
        ]);

        $jobVacancy = JobVacancy::findOrFail($id);
        $jobVacancy->status = $request->status;
        $jobVacancy->save();

        return response()->json(['message' => 'Job vacancy status updated successfully']);
    }
}
