<?php

namespace App\Http\Controllers;

use App\Models\ScholarshipTab;
use App\Models\ScholarshipForm;
use App\Models\ScholarshipApplication;
use App\Models\ScholarshipDocument;
use App\Mail\ScholarshipApplicationSubmitted;
use App\Mail\ScholarshipApplicationUnderReview;
use App\Mail\ScholarshipApplicationApproved;
use App\Mail\ScholarshipApplicationRejected;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ScholarshipController extends Controller
{
    protected $logChannel = 'scholarship';

    /**
     * Display the scholarship page.
     */
    public function index()
    {
        $tabs = ScholarshipTab::orderBy('order')->get();
        $activeForm = ScholarshipForm::where('is_active', true)->first();
        
        return view('scholarships.index', compact('tabs', 'activeForm'));
    }

    /**
     * Download a scholarship form.
     */
    public function downloadForm(ScholarshipForm $form)
    {
        if (!Storage::disk('public')->exists($form->file_path)) {
            abort(404);
        }

        return Storage::disk('public')->download($form->file_path, $form->name);
    }

    /**
     * Show the application form.
     */
    public function showApplicationForm()
    {
        Log::info('Accessing showApplicationForm method');

        try {
            $activeForm = ScholarshipForm::where('is_active', true)->first();
        
            if (!$activeForm) {
                Log::warning('No active scholarship form available');
                return view('scholarships.apply')->with('warning', 'No active scholarship form available at this time. Please check back later or contact the administrator.');
            }
        
            Log::info('Rendering scholarships.apply view');
            return view('scholarships.apply', compact('activeForm'));
        } catch (\Exception $e) {
            Log::error('Error in showApplicationForm: ' . $e->getMessage());
            return view('scholarships.apply')->with('error', 'An error occurred while loading the application form. Please try again later.');
        }
    }

    /**
     * Store a new scholarship application.
     */
    public function storeApplication(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'documents' => 'required|array|min:1',
            'documents.*' => 'required|file|mimes:pdf|max:5120'
        ]);

        try {
            DB::beginTransaction();

            // Check for existing application with the same email
            $existingApplication = ScholarshipApplication::where('email', $request->email)
                ->where('status', 'pending')
                ->first();

            if ($existingApplication) {
                // Add documents to existing application
                $application = $existingApplication;
            } else {
                // Create new application
                $application = new ScholarshipApplication([
                    'email' => $request->email,
                    'user_id' => auth()->id(),
                    'name' => auth()->user() ? auth()->user()->name : null,
                    'status' => 'pending'
                ]);
                $application->save();
            }

            // Upload and save documents
            foreach ($request->file('documents') as $document) {
                $path = $document->store('scholarship-documents', 'public');
                
                $scholarshipDocument = new ScholarshipDocument([
                    'scholarship_application_id' => $application->id,
                    'document_path' => $path,
                    'original_name' => $document->getClientOriginalName(),
                    'mime_type' => $document->getMimeType()
                ]);

                $application->documents()->save($scholarshipDocument);
            }

            DB::commit();

            // Send email notification
            Mail::to($request->email)->send(new ScholarshipApplicationSubmitted($application));

            return redirect()->route('scholarships.index')
                ->with('success', 'Your scholarship application has been submitted successfully. You will receive an email confirmation shortly.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to submit scholarship application', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to submit your application. Please try again later.')
                ->withInput();
        }
    }

    /**
     * Display the admin dashboard for scholarship applications.
     */
    public function adminIndex(Request $request)
    {
        $query = ScholarshipApplication::with(['documents']);

        // Search functionality
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Sorting
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $applications = $query->paginate(10);

        return view('admin.scholarships.index', compact('applications'));
    }

    /**
     * Show a specific application.
     */
    public function adminShow(ScholarshipApplication $application)
    {
        $application->load('documents');
        return view('admin.scholarships.show', compact('application'));
    }

    /**
     * Mark an application as under review.
     */
    public function markUnderReview(ScholarshipApplication $application)
    {
        $application->update(['status' => 'under_review']);
        
        // Send email notification
        Mail::to($application->email)->send(new ScholarshipApplicationUnderReview($application));
        
        return redirect()->back()->with('success', 'Application marked as under review. The applicant has been notified.');
    }

    /**
     * Approve an application.
     */
    public function approve(Request $request, ScholarshipApplication $application)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $application->update([
            'status' => 'approved',
            'admin_notes' => $request->admin_notes
        ]);
        
        // Send email notification
        Mail::to($application->email)->send(new ScholarshipApplicationApproved($application));
        
        return redirect()->back()->with('success', 'Application approved. The applicant has been notified.');
    }

    /**
     * Reject an application.
     */
    public function reject(Request $request, ScholarshipApplication $application)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $application->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes
        ]);
        
        // Send email notification
        Mail::to($application->email)->send(new ScholarshipApplicationRejected($application));
        
        return redirect()->back()->with('success', 'Application rejected. The applicant has been notified.');
    }

    /**
     * View a document.
     */
    public function viewDocument(ScholarshipDocument $document)
    {
        if (!Storage::disk('public')->exists($document->document_path)) {
            abort(404);
        }

        $file = Storage::disk('public')->get($document->document_path);
        $type = Storage::disk('public')->mimeType($document->document_path);

        return response($file, 200)->header('Content-Type', $type);
    }
}

