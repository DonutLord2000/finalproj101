<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use App\Models\VerificationRequest;
use App\Models\VerificationDocument;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VerificationController extends Controller
{
    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    public function index()
    {
        $pendingRequests = VerificationRequest::with(['user', 'documents'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $processedRequests = VerificationRequest::with(['user', 'documents'])
            ->whereIn('status', ['approved', 'rejected'])
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('admin.verification-requests.index', compact('pendingRequests', 'processedRequests'));
    }

    public function store(Request $request)
    {
        Log::info('Verification request initiated', ['user_id' => auth()->id()]);

        $request->validate([
            'documents' => 'required|array|min:1',
            'documents.*' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $existingRequest = auth()->user()->verificationRequests()->where('status', 'pending')->first();

        if ($existingRequest) {
            return redirect()->back()->with('error', 'You already have a pending verification request.');
        }

        try {
            DB::beginTransaction();

            $verificationRequest = auth()->user()->verificationRequests()->create([
                'status' => 'pending'
            ]);

            Log::info('Verification request created', ['request_id' => $verificationRequest->id]);

            foreach ($request->file('documents') as $document) {
                $path = $document->store('verification-documents', 'private');
                
                $verificationDocument = new VerificationDocument([
                    'document_path' => $path,
                    'original_name' => $document->getClientOriginalName(),
                    'mime_type' => $document->getMimeType()
                ]);

                $verificationRequest->documents()->save($verificationDocument);

                Log::info('Verification document uploaded', [
                    'document_id' => $verificationDocument->id,
                    'original_name' => $verificationDocument->original_name
                ]);
            }

            DB::commit();
            Log::info('Verification request submitted successfully', ['request_id' => $verificationRequest->id]);

            $this->activityLogService->log('verification request', 'Submitted verification request');

            return redirect()->back()->with('success', 'Verification request submitted successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to submit verification request', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Failed to submit verification request. Please try again. Error: ' . $e->getMessage());
        }
    }

    public function cancel(VerificationRequest $verificationRequest)
    {
        if ($verificationRequest->user_id !== auth()->id() || $verificationRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'You cannot cancel this verification request.');
        }

        $verificationRequest->delete();

        $this->activityLogService->log('verification request', 'Cancelled verification request');

        return redirect()->back()->with('success', 'Verification request cancelled successfully.');
    }   

    public function review(Request $request, VerificationRequest $verificationRequest)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $verificationRequest->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes
        ]);

        if ($request->status === 'approved') {
            $verificationRequest->user->profile->update(['is_verified' => true]);
        }

        $this->activityLogService->log('verification', 'Reviewed verification request: ' . $request->status);

        return redirect()->back()->with('success', 'Verification request updated successfully');
    }

    public function showDocument($id)
    {
        $document = VerificationDocument::findOrFail($id);
        
        if (!Storage::disk('private')->exists($document->document_path)) {
            abort(404);
        }

        $file = Storage::disk('private')->get($document->document_path);
        $type = Storage::disk('private')->mimeType($document->document_path);

        return response($file, 200)->header('Content-Type', $type);
    }

    public function approve(VerificationRequest $verificationRequest)
    {
        $verificationRequest->update(['status' => 'approved']);
        $verificationRequest->user->profile->update(['is_verified' => true]);

        $this->activityLogService->log('verification', 'Approved verification request for user: ' . $verificationRequest->user->name);

        return redirect()->back()->with('success', 'Verification request approved successfully');
    }

    public function reject(VerificationRequest $verificationRequest)
    {
        $verificationRequest->update(['status' => 'rejected']);
        $verificationRequest->user->profile->update(['is_verified' => false]);

        $this->activityLogService->log('verification', 'Rejected verification request for user: ' . $verificationRequest->user->name);

        return redirect()->back()->with('success', 'Verification request rejected successfully');
    }
}