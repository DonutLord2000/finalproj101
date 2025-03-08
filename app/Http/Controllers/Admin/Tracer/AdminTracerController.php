<?php

namespace App\Http\Controllers\Admin\Tracer;

use App\Models\Alumnus;
use App\Models\PendingResponse;
use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class AdminTracerController extends Controller
{
    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    public function index()
    {
        $pendingResponses = PendingResponse::where('status', 'pending')->get();
        $processedResponses = PendingResponse::whereIn('status', ['approved', 'rejected'])->get();
        return view('admin.pending-responses', compact('pendingResponses', 'processedResponses'));
    }

    public function show(PendingResponse $response)
    {
        $response->load('additionalAnswers');
        $combinedData = array_merge($response->response_data, $response->additionalAnswers->additional_data ?? []);
        return response()->json(['response_data' => $combinedData]);
    }

    public function edit(PendingResponse $response)
    {
        try {
            $response->load('additionalAnswers');
            $combinedData = array_merge(
                $response->response_data ?? [],
                $response->additionalAnswers->additional_data ?? []
            );
            
            return response()->json([
                'success' => true,
                'response_data' => $combinedData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading response data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, PendingResponse $response)
    {
        try {
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
            
            $response->update(['response_data' => $validatedData]);
            
            if ($response->additionalAnswers) {
                $response->additionalAnswers->update(['additional_data' => $additionalData]);
            } else {
                $response->additionalAnswers()->create(['additional_data' => $additionalData]);
            }

            $this->activityLogService->log('tracer', 'Updated pending response for: ' . $validatedData['name']);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating response',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function approve(PendingResponse $response)
    {
        $alumniData = $response->response_data;
        Alumnus::create($alumniData);
        $response->update(['status' => 'approved']);

        $this->activityLogService->log('tracer', 'Approved pending response and added to alumni: ' . $alumniData['name']);

        return redirect()->route('admin.pending-responses')->with('success', 'Response approved and added to alumni table.');
    }

    public function reject(PendingResponse $response)
    {
        $response->update(['status' => 'rejected']);

        $this->activityLogService->log('tracer', 'Rejected pending response for: ' . $response->response_data['name']);

        return redirect()->route('admin.pending-responses')->with('success', 'Response rejected.');
    }
}