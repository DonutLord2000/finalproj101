<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\Experience;
use App\Models\Education;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('profile')->whereHas('profile');

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('profile', function ($q) use ($search) {
                      $q->where('address', 'like', "%{$search}%");
                  });
            });
        }

        $showVerified = $request->input('show_verified') === 'true';

        if ($showVerified) {
            $query->whereHas('profile', function ($q) {
                $q->where('is_verified', true);
            });
        }

        $profiles = $query->paginate(20);

        if ($request->ajax()) {
            return view('alumni.partials.profile-cards', compact('profiles'))->render();
        }

        return view('alumni.all-profiles', compact('profiles'));
    }
    
    public function show(User $user)
    {
        $user->load(['profile', 'experiences', 'education', 'verificationRequests' => function ($query) {
            $query->latest();
        }]);

        return view('alumni.show-profile', compact('user'));
    }
    
    public function edit()
    {
        $user = auth()->user()->load(['profile', 'experiences', 'education']);
        $showEula = false;
        
        // Check if user has a profile and if they've accepted the EULA
        if (!$user->profile || !$user->profile->eula_accepted) {
            $showEula = true;
        }
        
        return view('profile.edit', compact('user', 'showEula'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'profile_picture' => 'nullable|image|max:1024',
            'cover_picture' => 'nullable|image|max:2048',
            'address' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
        ]);

        $user = auth()->user();
        $profile = $user->profile ?? new Profile(['user_id' => $user->id]);

        if ($request->hasFile('profile_picture')) {
            if ($profile->profile_picture) {
                Storage::delete($profile->profile_picture);
            }
            $profile->profile_picture = $request->file('profile_picture')->store('profile-pictures', 'public');
        }

        if ($request->hasFile('cover_picture')) {
            if ($profile->cover_picture) {
                Storage::delete($profile->cover_picture);
            }
            $profile->cover_picture = $request->file('cover_picture')->store('cover-pictures', 'public');
        }

        $profile->fill($request->only(['address', 'contact_number', 'bio']));
        $profile->save();

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully');
    }

    public function acceptEula(Request $request)
    {
        $user = auth()->user();
        $profile = $user->profile ?? new Profile(['user_id' => $user->id]);
        
        $profile->eula_accepted = true;
        $profile->save();
        
        return redirect()->route('profile.edit')->with('success', 'EULA accepted successfully');
    }

    public function addExperience(Request $request)
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

        return redirect()->route('profile.edit')->with('success', 'Experience added successfully');
    }

    public function addEducation(Request $request)
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

        return redirect()->route('profile.edit')->with('success', 'Education added successfully');
    }

    public function destroyExperience($id)
    {
        $experience = auth()->user()->experiences()->findOrFail($id);
        $experience->delete();

        return redirect()->route('profile.edit')->with('success', 'Experience deleted successfully');
    }

    public function destroyEducation($id)
    {
        $education = auth()->user()->education()->findOrFail($id);
        $education->delete();

        return redirect()->route('profile.edit')->with('success', 'Education deleted successfully');
    }
}