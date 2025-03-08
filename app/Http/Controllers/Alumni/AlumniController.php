<?php

namespace App\Http\Controllers\Alumni;

use App\Models\User;
use App\Models\Alumnus;
use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class AlumniController extends Controller
{
    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    public function index(Request $request)
    {
        $sortColumn = $request->get('sort', 'name');
        $sortDirection = $request->get('direction', 'asc');

        // Validate sort column and direction
        $validSortColumns = ['name', 'gender', 'degree_program', 'employment_status', 'industry', 'year_graduated', 'marital_status', 'email', 'phone', 'major', 'minor', 'gpa', 'job_title', 'company', 'nature_of_work', 'tenure_status', 'monthly_salary'];
        $validSortDirections = ['asc', 'desc'];

        if (!in_array($sortColumn, $validSortColumns) || !in_array($sortDirection, $validSortDirections)) {
            $sortColumn = 'name';
            $sortDirection = 'asc';
        }

        // Get search query
        $search = $request->get('search');

        // Fetch alumni with search and sort
        $alumni = Alumnus::when($search, function ($query) use ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('degree_program', 'like', '%' . $search . '%')
                  ->orWhere('industry', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%')
                  ->orWhere('marital_status', 'like', '%' . $search . '%')
                  ->orWhere('job_title', 'like', '%' . $search . '%')
                  ->orWhere('company', 'like', '%' . $search . '%')
                  ->orWhere('year_graduated', 'like', '%' . $search . '%');
            });
        })
        ->when($sortColumn && $sortDirection, function ($query) use ($sortColumn, $sortDirection) {
            return $query->orderBy($sortColumn, $sortDirection);
        })
        ->paginate(15);

        $additionalColumns = [
            'marital_status' => 'Marital Status',
            'email' => 'Email',
            'phone' => 'Phone',
            'major' => 'Major',
            'minor' => 'Minor',
            'gpa' => 'GPA',
            'job_title' => 'Job Title',
            'company' => 'Company',
            'nature_of_work' => 'Nature of Work',
            'tenure_status' => 'Tenure Status',
            'employment_sector' => 'Employment Sector',
            'monthly_salary' => 'Monthly Salary'
        ];

        // Check if the request is an AJAX request
        if ($request->ajax()) {
            return view('alumni.partials.table_rows', compact('alumni', 'additionalColumns', 'sortColumn', 'sortDirection'))->render();
        }

        // Pass the alumni, sort column, sort direction, search term, and additional columns to the main view
        return view('alumni.index', compact('alumni', 'sortColumn', 'sortDirection', 'search', 'additionalColumns'));
    }

    public function show(Alumnus $alumnus)
    {
        return view('alumni.show', compact('alumnus'));
    }

    public function edit(Alumnus $alumnus)
    {
        return view('alumni.edit', compact('alumnus'));
    }

    public function update(Request $request, Alumnus $alumnus)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
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

        $alumnus->update($validated);

        $this->activityLogService->log('alumni', 'Updated alumnus: ' . $alumnus->name);

        return redirect()->route('alumni.show', $alumnus)->with('success', 'Alumnus updated successfully.');
    }

    public function destroy(Alumnus $alumnus)
    {
        $this->activityLogService->log('alumni', 'Deleted alumnus: ' . $alumnus->name);

        $alumnus->delete();
        return redirect()->route('alumni.index')->with('success', 'Alumnus deleted successfully.');
    }

    public function create()
    {
        return view('alumni.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
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

        $alumnus = Alumnus::create($validated);

        $this->activityLogService->log('alumni', 'Created new alumnus: ' . $alumnus->name);

        return redirect()->route('alumni.show', $alumnus)->with('success', 'Alumnus created successfully.');
    }
}