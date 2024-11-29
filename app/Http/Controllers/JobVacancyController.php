<?php

namespace App\Http\Controllers;

use App\Models\JobVacancy;
use Illuminate\Http\Request;

class JobVacancyController extends Controller
{
    public function index()
    {
        return JobVacancy::all();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'institution_id' => 'required|exists:institutions,institution_id',
            'status' => 'required|string',
        ]);

        $jobVacancy = JobVacancy::create($validatedData);

        return response()->json($jobVacancy, 201);
    }

    public function show(JobVacancy $jobVacancy)
    {
        return $jobVacancy;
    }

    public function update(Request $request, JobVacancy $jobVacancy)
    {
        $validatedData = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'requirements' => 'sometimes|required|string',
            'institution_id' => 'sometimes|required|exists:institutions,institution_id',
            'status' => 'sometimes|required|string',
        ]);

        $jobVacancy->update($validatedData);

        return response()->json($jobVacancy, 200);
    }

    public function destroy(JobVacancy $jobVacancy)
    {
        $jobVacancy->delete();

        return response()->json(null, 204);
    }
}
