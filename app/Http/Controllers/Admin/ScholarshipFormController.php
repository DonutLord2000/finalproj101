<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScholarshipForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ScholarshipFormController extends Controller
{
    /**
     * Display a listing of the forms.
     */
    public function index()
    {
        $forms = ScholarshipForm::all();
        return view('admin.scholarships.forms.index', compact('forms'));
    }

    /**
     * Show the form for creating a new form.
     */
    public function create()
    {
        return view('admin.scholarships.forms.create');
    }

    /**
     * Store a newly created form.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'form_file' => 'required|file|mimes:pdf|max:5120',
            'is_active' => 'boolean'
        ]);

        $path = $request->file('form_file')->store('scholarship-forms', 'public');

        // If this form is active, deactivate all other forms
        if ($request->has('is_active') && $request->is_active) {
            ScholarshipForm::where('is_active', true)->update(['is_active' => false]);
        }

        ScholarshipForm::create([
            'name' => $request->name,
            'file_path' => $path,
            'is_active' => $request->has('is_active') ? $request->is_active : false
        ]);

        return redirect()->route('admin.scholarship-forms.index')
            ->with('success', 'Scholarship form uploaded successfully.');
    }

    /**
     * Set a form as active.
     */
    public function setActive(ScholarshipForm $scholarshipForm)
    {
        // Deactivate all forms
        ScholarshipForm::where('is_active', true)->update(['is_active' => false]);
        
        // Activate the selected form
        $scholarshipForm->update(['is_active' => true]);

        return redirect()->route('admin.scholarship-forms.index')
            ->with('success', 'Scholarship form set as active.');
    }

    /**
     * Remove the form.
     */
    public function destroy(ScholarshipForm $scholarshipForm)
    {
        // Delete the file
        if (Storage::disk('public')->exists($scholarshipForm->file_path)) {
            Storage::disk('public')->delete($scholarshipForm->file_path);
        }

        $scholarshipForm->delete();

        return redirect()->route('admin.scholarship-forms.index')
            ->with('success', 'Scholarship form deleted successfully.');
    }
}

