<?php

namespace App\Http\Controllers\Admin\Tracer;

use App\Models\PendingResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TracerStudyController extends Controller
{
    public function showForm()
    {
        return view('tracer-study.form');
    }

    public function submitForm(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'nullable|string|max:255',
            'year_graduated' => 'required|integer',
            'age' => 'nullable|integer',
            'gender' => 'nullable|in:Male,Female,Other',
            'marital_status' => 'nullable|string|max:255',
            'current_location' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'degree_program' => 'required|string|max:255',
            'major' => 'nullable|string|max:255',
            'minor' => 'nullable|string|max:255',
            'gpa' => 'nullable|numeric|between:0,4.00',
            'employment_status' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'industry' => 'nullable|string|max:255',
            'nature_of_work' => 'nullable|string|max:255',
            'employment_sector' => 'nullable|string|max:255',
            'tenure_status' => 'nullable|string|max:255',
            'monthly_salary' => 'nullable|numeric',
        ]);

        $additionalData = $request->except(array_keys($validatedData));

        $pendingResponse = PendingResponse::create([
            'response_data' => $validatedData,
            'status' => 'pending',
        ]);

        $pendingResponse->additionalAnswers()->create([
            'additional_data' => $additionalData,
        ]);

        return redirect()->route('tracer-study.thank-you');
    }

    public function thankYou()
    {
        return view('tracer-study.thank-you');
    }
}

