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
        // Hardcoded scholarship tabs
        $tabs = [
            (object)[
                'id' => 'overview',
                'name' => 'Overview',
                'order' => 1,
                'content' => '<p class="mb-4">The GRC-MLALAF Scholarship Program is a partnership between GRC and Motortrade Life and Livelihood Assistance Foundation aimed at providing educational opportunities to deserving students.</p>
                <p class="mb-4">This scholarship covers 100% of tuition and other school fees for the entire duration of the program, subject to maintaining the required academic performance.</p>'
            ],
            (object)[
                'id' => 'eligibility',
                'name' => 'Eligibility',
                'order' => 2,
                'content' => '<ul class="list-disc pl-5 mb-4 space-y-2">
                    <li>Must be a Filipino citizen</li>
                    <li>Must have a general weighted average of at least 85% or its equivalent</li>
                    <li>Must come from a low-income family (annual family income not exceeding PHP 300,000)</li>
                    <li>Must not be a recipient of any other scholarship</li>
                    <li>Must be willing to comply with the terms and conditions of the scholarship</li>
                </ul>'
            ],
            (object)[
                'id' => 'requirements',
                'name' => 'Requirements',
                'order' => 3,
                'content' => '<ul class="list-disc pl-5 mb-4 space-y-2">
                    <li>Accomplished application form</li>
                    <li>Recent 2x2 ID picture</li>
                    <li>Certificate of Registration or Enrollment</li>
                    <li>Authenticated copy of grades from the previous semester</li>
                    <li>Certificate of Good Moral Character</li>
                    <li>Proof of family income (ITR, Certificate of Employment with Compensation, etc.)</li>
                    <li>Barangay Certificate of Residency</li>
                </ul>'
            ],
            (object)[
                'id' => 'process',
                'name' => 'Application Process',
                'order' => 4,
                'content' => '<ol class="list-decimal pl-5 mb-4 space-y-2">
                    <li>Download and fill out the application form</li>
                    <li>Prepare all required documents</li>
                    <li>Submit the application form and requirements through this portal</li>
                    <li>Wait for the evaluation of your application</li>
                    <li>If shortlisted, attend the interview</li>
                    <li>Wait for the final results</li>
                </ol>'
            ],
        ];
        
        // Get active form from database
        $activeForm = ScholarshipForm::where('is_active', true)->first();
        
        return view('scholarships.index', compact('tabs', 'activeForm'));
    }

    /**
     * Download a scholarship form.
     */
    public function downloadForm($formId = null)
    {
        try {
            // If a specific form ID is provided, use that
            if ($formId) {
                $form = ScholarshipForm::findOrFail($formId);
            } else {
                // Otherwise, get the active form
                $form = ScholarshipForm::where('is_active', true)->first();
                
                if (!$form) {
                    return redirect()->back()->with('error', 'No active scholarship form is available.');
                }
            }
            
            $disk = $form->storage_disk ?? 'public';
            
            if ($disk === 's3') {
                // Get from S3
                if (!Storage::disk('s3')->exists($form->file_path)) {
                    return redirect()->back()->with('error', 'The scholarship form file could not be found on S3.');
                }
                
                $tempFile = tempnam(sys_get_temp_dir(), 'form_');
                file_put_contents($tempFile, Storage::disk('s3')->get($form->file_path));
                
                return response()->download($tempFile, $form->name)->deleteFileAfterSend(true);
            } else {
                // Get from local storage
                if (!file_exists(public_path($form->file_path))) {
                    return redirect()->back()->with('error', 'The scholarship form file could not be found in local storage.');
                }
                
                return response()->download(public_path($form->file_path), $form->name);
            }
        } catch (\Exception $e) {
            Log::error('Error downloading form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error downloading form: ' . $e->getMessage());
        }
    }

    /**
     * Show the application form.
     */
    public function showApplicationForm()
    {
        Log::info('Accessing showApplicationForm method');
    
        try {
            // Get active form from database
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
    
            // Upload and save documents - use public disk as fallback if S3 fails
            foreach ($request->file('documents') as $document) {
                try {
                    // Try S3 first
                    $path = Storage::disk('s3')->put('scholarship-documents', $document);
                    $disk = 's3';
                } catch (\Exception $e) {
                    // Fallback to public storage
                    $path = 'scholarship-documents/' . time() . '_' . $document->getClientOriginalName();
                    $document->move(public_path('scholarship-documents'), time() . '_' . $document->getClientOriginalName());
                    $disk = 'public';
                }
                
                $scholarshipDocument = new ScholarshipDocument([
                    'scholarship_application_id' => $application->id,
                    'document_path' => $path,
                    'original_name' => $document->getClientOriginalName(),
                    'mime_type' => $document->getMimeType(),
                    'disk' => $disk // Add a disk field to track where the file is stored
                ]);
    
                $application->documents()->save($scholarshipDocument);
            }
    
            DB::commit();
    
            // Send email notification with better error handling
            try {
                Log::info('Attempting to send application submitted email to: ' . $request->email);
                Mail::to($request->email)->send(new ScholarshipApplicationSubmitted($application));
                Log::info('Application submitted email sent successfully to: ' . $request->email);
            } catch (\Exception $emailException) {
                Log::error('Failed to send application submitted email', [
                    'email' => $request->email,
                    'error' => $emailException->getMessage(),
                    'trace' => $emailException->getTraceAsString()
                ]);
                // Continue execution even if email fails
            }
    
            return redirect()->route('scholarships.index')
                ->with('success', 'Your scholarship application has been submitted successfully.');
    
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to submit scholarship application', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
    
            return redirect()->back()
                ->with('error', 'Failed to submit your application: ' . $e->getMessage())
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
    public function markUnderReview(Request $request, ScholarshipApplication $application)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000'
        ]);
        
        $application->update([
            'status' => 'under_review',
            'admin_notes' => $request->admin_notes
        ]);
        
        // Fix: Use application email and add better error handling
        try {
            Log::info('Attempting to send under review email to: ' . $application->email);
            Mail::to($application->email)->send(new ScholarshipApplicationUnderReview($application));
            Log::info('Under review email sent successfully to: ' . $application->email);
        } catch (\Exception $e) {
            Log::error('Failed to send under review email', [
                'email' => $application->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            // Continue execution even if email fails
        }
        
        return redirect()->route('admin.scholarships.index')->with('success', 'Application marked as under review.');
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
        
        // Fix: Use application email and add better error handling
        try {
            Log::info('Attempting to send approval email to: ' . $application->email);
            Mail::to($application->email)->send(new ScholarshipApplicationApproved($application));
            Log::info('Approval email sent successfully to: ' . $application->email);
        } catch (\Exception $e) {
            Log::error('Failed to send approval email', [
                'email' => $application->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            // Continue execution even if email fails
        }
        
        return redirect()->route('admin.scholarships.index')->with('success', 'Application approved.');
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
        
        // Fix: Use application email and add better error handling
        try {
            Log::info('Attempting to send rejection email to: ' . $application->email);
            Mail::to($application->email)->send(new ScholarshipApplicationRejected($application));
            Log::info('Rejection email sent successfully to: ' . $application->email);
        } catch (\Exception $e) {
            Log::error('Failed to send rejection email', [
                'email' => $application->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            // Continue execution even if email fails
        }
        
        return redirect()->route('admin.scholarships.index')->with('success', 'Application rejected.');
    }

    /**
     * View a document.
     */
    public function viewDocument(ScholarshipDocument $document)
    {
        $disk = $document->disk ?? 'public';
        
        try {
            if ($disk === 's3') {
                // Fetch from S3
                if (!Storage::disk('s3')->exists($document->document_path)) {
                    abort(404, 'Document not found on S3.');
                }
                $file = Storage::disk('s3')->get($document->document_path);
            } else {
                // Fetch from public directory
                $filePath = public_path($document->document_path);
                if (!file_exists($filePath)) {
                    abort(404, 'Document not found in public directory.');
                }
                $file = file_get_contents($filePath);
            }
            
            $type = $document->mime_type ?? 'application/pdf';
            return response($file, 200)->header('Content-Type', $type);
        } catch (\Exception $e) {
            Log::error('Error viewing document: ' . $e->getMessage());
            abort(500, 'Error viewing document: ' . $e->getMessage());
        }
    }

    /**
 * Display the form management page.
 */
public function formManagement()
{
    $forms = ScholarshipForm::orderBy('created_at', 'desc')->get();
    return view('admin.scholarships.forms', compact('forms'));
}

/**
 * Upload a new scholarship form.
 */
public function uploadForm(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'form_file' => 'required|file|mimes:pdf|max:10240',
        'is_active' => 'nullable|boolean'
    ]);

    try {
        // If the new form is active, deactivate all other forms
        if ($request->has('is_active')) {
            ScholarshipForm::where('is_active', true)->update(['is_active' => false]);
        }

        // Get the file
        $file = $request->file('form_file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        
        // Store the file in S3
        $path = Storage::disk('s3')->put('scholarship-forms', $file);
        
        // Create the form record
        ScholarshipForm::create([
            'name' => $request->name,
            'file_path' => $path,
            'is_active' => $request->has('is_active'),
            'storage_disk' => 's3'
        ]);

        return redirect()->route('admin.scholarships.forms')
            ->with('success', 'Scholarship form uploaded successfully to S3.');
    } catch (\Exception $e) {
        // Log the error
        Log::error('Failed to upload scholarship form', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        // Try local storage as fallback
        try {
            // Store the file locally
            $fileName = time() . '_' . $request->file('form_file')->getClientOriginalName();
            $filePath = 'scholarship-forms/' . $fileName;
            $request->file('form_file')->move(public_path('scholarship-forms'), $fileName);

            // Create the form record
            ScholarshipForm::create([
                'name' => $request->name,
                'file_path' => $filePath,
                'is_active' => $request->has('is_active'),
                'storage_disk' => 'public'
            ]);

            return redirect()->route('admin.scholarships.forms')
                ->with('success', 'Scholarship form uploaded successfully to local storage.');
        } catch (\Exception $innerException) {
            Log::error('Failed to upload scholarship form to local storage', [
                'error' => $innerException->getMessage(),
                'trace' => $innerException->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to upload form: ' . $e->getMessage() . ' (Local fallback also failed: ' . $innerException->getMessage() . ')')
                ->withInput();
        }
    }
}

/**
 * Delete a scholarship form.
 */
public function deleteForm(ScholarshipForm $form)
{
    try {
        $disk = $form->storage_disk ?? 'public';
        
        if ($disk === 's3') {
            // Delete from S3
            if (Storage::disk('s3')->exists($form->file_path)) {
                Storage::disk('s3')->delete($form->file_path);
            }
        } else {
            // Delete from local storage
            if (file_exists(public_path($form->file_path))) {
                unlink(public_path($form->file_path));
            }
        }

        // Delete the record
        $form->delete();

        return redirect()->route('admin.scholarships.forms')
            ->with('success', 'Scholarship form deleted successfully.');
    } catch (\Exception $e) {
        Log::error('Failed to delete scholarship form', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return redirect()->back()
            ->with('error', 'Failed to delete form: ' . $e->getMessage());
    }
}

/**
 * Toggle the active status of a form.
 */
public function toggleFormStatus(ScholarshipForm $form)
{
    try {
        // If activating this form, deactivate all others
        if (!$form->is_active) {
            ScholarshipForm::where('is_active', true)->update(['is_active' => false]);
        }

        // Toggle the status
        $form->update(['is_active' => !$form->is_active]);

        return redirect()->route('admin.scholarships.forms')
            ->with('success', 'Form status updated successfully.');
    } catch (\Exception $e) {
        Log::error('Failed to update form status', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return redirect()->back()
            ->with('error', 'Failed to update form status: ' . $e->getMessage());
    }
}
}