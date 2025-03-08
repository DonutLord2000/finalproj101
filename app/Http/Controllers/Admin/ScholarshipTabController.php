<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScholarshipTab;
use Illuminate\Http\Request;

class ScholarshipTabController extends Controller
{
    /**
     * Display a listing of the tabs.
     */
    public function index()
    {
        $tabs = ScholarshipTab::orderBy('order')->get();
        return view('admin.scholarships.tabs.index', compact('tabs'));
    }

    /**
     * Show the form for creating a new tab.
     */
    public function create()
    {
        return view('admin.scholarships.tabs.create');
    }

    /**
     * Store a newly created tab.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'order' => 'required|integer|min:0'
        ]);

        ScholarshipTab::create($request->all());

        return redirect()->route('admin.scholarship-tabs.index')
            ->with('success', 'Scholarship tab created successfully.');
    }

    /**
     * Show the form for editing the tab.
     */
    public function edit(ScholarshipTab $scholarshipTab)
    {
        return view('admin.scholarships.tabs.edit', compact('scholarshipTab'));
    }

    /**
     * Update the tab.
     */
    public function update(Request $request, ScholarshipTab $scholarshipTab)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'order' => 'required|integer|min:0'
        ]);

        $scholarshipTab->update($request->all());

        return redirect()->route('admin.scholarship-tabs.index')
            ->with('success', 'Scholarship tab updated successfully.');
    }

    /**
     * Remove the tab.
     */
    public function destroy(ScholarshipTab $scholarshipTab)
    {
        $scholarshipTab->delete();

        return redirect()->route('admin.scholarship-tabs.index')
            ->with('success', 'Scholarship tab deleted successfully.');
    }
}

