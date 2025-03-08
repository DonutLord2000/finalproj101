<?php

namespace App\Http\Controllers\Alumni;

use App\Models\Experience;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExperienceController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'employment_type' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'current_role' => 'boolean',
            'location' => 'required|string|max:255',
            'location_type' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        auth()->user()->experiences()->create($validated);

        return redirect()->back()->with('success', 'Experience added successfully');
    }

    public function update(Request $request, Experience $experience)
    {
        $this->authorize('update', $experience);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'employment_type' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'current_role' => 'boolean',
            'location' => 'required|string|max:255',
            'location_type' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $experience->update($validated);

        return redirect()->back()->with('success', 'Experience updated successfully');
    }

    public function destroy(Experience $experience)
    {
        $this->authorize('delete', $experience);
        
        $experience->delete();

        return redirect()->back()->with('success', 'Experience deleted successfully');
    }
}