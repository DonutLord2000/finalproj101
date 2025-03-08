<?php

namespace App\Http\Controllers\Alumni;

use App\Models\Education;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EducationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school' => 'required|string|max:255',
            'degree' => 'required|string|max:255',
            'field_of_study' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'grade' => 'nullable|string|max:255',
            'activities' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        auth()->user()->education()->create($validated);

        return redirect()->back()->with('success', 'Education added successfully');
    }

    public function update(Request $request, Education $education)
    {
        $this->authorize('update', $education);
        
        $validated = $request->validate([
            'school' => 'required|string|max:255',
            'degree' => 'required|string|max:255',
            'field_of_study' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'grade' => 'nullable|string|max:255',
            'activities' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $education->update($validated);

        return redirect()->back()->with('success', 'Education updated successfully');
    }

    public function destroy(Education $education)
    {
        $this->authorize('delete', $education);
        
        $education->delete();

        return redirect()->back()->with('success', 'Education deleted successfully');
    }
}