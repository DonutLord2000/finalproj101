<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\Experience;
use App\Models\Education;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Support\Facades\DB; // Added for database queries
use Illuminate\Support\Facades\Http; // Added for OpenAI API calls
use Illuminate\Support\Facades\Log; // Added for logging

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

        $profiles = $query->paginate(26);

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

        // Fetch distinct graduation years from the alumni table
        $alumniYears = DB::table('alumni')
                        ->distinct()
                        ->orderBy('year_graduated', 'desc')
                        ->pluck('year_graduated')
                        ->toArray();
        
        return view('profile.edit', compact('user', 'showEula', 'alumniYears'));
    }

    private function getS3Url($path)
    {
        if (!$path) return null;
        return Storage::disk('s3')->url($path);
    }

    // Update the update method to store the full path
    public function update(Request $request)
    {
        $request->validate([
            'profile_picture' => 'nullable|image|max:2048',
            'cover_picture' => 'nullable|image|max:2048',
            'address' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:11',
            'bio' => 'nullable|string|max:1000',
        ]);

        $user = auth()->user();
        $profile = $user->profile ?? new Profile(['user_id' => $user->id]);

        // Handle profile picture upload to S3
        if ($request->hasFile('profile_picture')) {
            if ($profile->profile_picture) {
                Storage::disk('s3')->delete($profile->profile_picture);
            }
            $path = $request->file('profile_picture')->store('profile-pictures', 's3');
            $profile->profile_picture = $path; // Store the path only
        }

        // Handle cover picture upload to S3
        if ($request->hasFile('cover_picture')) {
            if ($profile->cover_picture) {
                Storage::disk('s3')->delete($profile->cover_picture);
            }
            $path = $request->file('cover_picture')->store('cover-pictures', 's3');
            $profile->cover_picture = $path; // Store the path only
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

    /**
     * Handles the career path prediction and personal insights using OpenAI.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function predictCareer(Request $request)
    {
        $user = auth()->user()->load(['experiences', 'education']);

        // 1. Check for minimum data (at least one experience and one education entry)
        if ($user->experiences->isEmpty() || $user->education->isEmpty()) {
            return response()->json(['error' => 'Please add at least one experience and one education entry to get a prediction.'], 400);
        }

        $yearFilter = $request->input('year_filter', 'all'); // 'all' or 'specific'
        $specificYear = $request->input('specific_year');

        // 2. Fetch alumni data from the 'alumni' table
        $alumniQuery = DB::table('alumni')
            ->select('degree_program', 'major', 'year_graduated', 'job_title', 'industry');

        if ($yearFilter === 'specific' && $specificYear) {
            $alumniQuery->where('year_graduated', $specificYear);
        }
        $alumniData = $alumniQuery->get();

        if ($alumniData->isEmpty()) {
            return response()->json(['error' => 'No alumni data found for the selected criteria. Please try a different year or "all years".'], 404);
        }

        // 3. Prepare prompt for OpenAI
        $userExperience = $user->experiences->map(function($exp) {
            return "Title: {$exp->title}, Company: {$exp->company}, Type: {$exp->employment_type}, Dates: {$exp->start_date->format('Y')} - " . ($exp->current_role ? 'Present' : ($exp->end_date ? $exp->end_date->format('Y') : 'Unknown'));
        })->implode('; ');

        $userEducation = $user->education->map(function($edu) {
            return "School: {$edu->school}, Degree: {$edu->degree} in {$edu->field_of_study}, Dates: {$edu->start_date->format('Y')} - " . ($edu->end_date ? $edu->end_date->format('Y') : 'Present');
        })->implode('; ');

        $alumniInfo = $alumniData->map(function($alumni) {
            return "Degree: {$alumni->degree_program}, Major: {$alumni->major}, Graduated: {$alumni->year_graduated}, Job: {$alumni->job_title}, Industry: {$alumni->industry}";
        })->implode('; ');

        $prompt = "Based on the user's current profile (experience and education) and the provided alumni data, provide both personal insights and a career path prediction.

        **Personal Insights:**
        Gently describe the user's strengths, potential career directions, and how their background might be valuable. Keep it concise and positive.

        **Career Path Prediction:**
        Provide the career path as a bulleted list, with each bullet point describing a potential stage or role at specific timeframes (e.g., 'After 2 years:', 'After 5 years:', 'After 10 years:'). Make sure to always include at least three distinct timeframes and career stages.

        **Chart Data Generation:**
        Also, generate data for two charts based *only* on the provided 'Alumni Data'.
        1.  **Pie Chart**: Show the distribution of 'Industry' among the alumni.
        2.  **Bar Chart**: Show the distribution of 'Job Title' among the alumni.
        
        Ensure the chart data is significant and directly reflects the trends in the provided alumni data. If a category has very few entries, you can group it into 'Other'.

        **User's Profile:**
        Experience: {$userExperience}
        Education: {$userEducation}

        **Alumni Data ({$yearFilter} years):**
        {$alumniInfo}

        Format your entire response as a JSON object with three keys: 'insight' (string), 'prediction' (string, containing the bulleted career path), and 'chart_data' (object).
        The 'chart_data' object should contain 'pie_chart' (array of {label: string, value: number}) and 'bar_chart' (array of {label: string, value: number}).

        Example JSON structure:
        {
            \"insight\": \"Your diverse experience in X and strong educational background in Y suggest a strong foundation for roles in Z.\",
            \"prediction\": \"- After 2 years: [Role suggestion]\\n- After 5 years: [Role suggestion]\\n- After 10 years: [Role suggestion]\",
            \"chart_data\": {
                \"pie_chart\": [{\"label\": \"Software Industry\", \"value\": 45}, {\"label\": \"Healthcare Industry\", \"value\": 25}],
                \"bar_chart\": [{\"label\": \"Software Engineer\", \"value\": 30}, {\"label\": \"Project Manager\", \"value\": 20}]
            }
        }";

        // 4. Call OpenAI API
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini', // Using gpt-4o-mini as it's cost-effective and often sufficient
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a career prediction and insights AI. Provide personal insights, career path predictions, and data for charts based on user and alumni data. Ensure the output is a valid JSON object. The career path should be a bulleted list with timeframes. The chart data must be derived directly from the provided alumni data.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.7,
                'max_tokens' => 1500, // Increased max_tokens for more detailed response and chart data
                'response_format' => ['type' => 'json_object'], // Request JSON output
            ]);

            if ($response->failed()) {
                Log::error('OpenAI API error for prediction: ' . $response->body());
                return response()->json(['error' => 'Failed to get prediction from AI service. Please check your OpenAI API key and network connection.'], 500);
            }

            $responseData = $response->json();
            $aiResponseContent = $responseData['choices'][0]['message']['content'] ?? null;

            // Log token usage
            if (isset($responseData['usage'])) {
                Log::info('OpenAI Token Usage for Career Prediction:', [
                    'prompt_tokens' => $responseData['usage']['prompt_tokens'] ?? 'N/A',
                    'completion_tokens' => $responseData['usage']['completion_tokens'] ?? 'N/A',
                    'total_tokens' => $responseData['usage']['total_tokens'] ?? 'N/A',
                ]);
            }


            if (is_null($aiResponseContent)) {
                Log::error('OpenAI response content is null or missing.');
                return response()->json(['error' => 'AI did not return a valid response content.'], 500);
            }

            // Attempt to decode the JSON response from AI
            $parsedResponse = json_decode($aiResponseContent, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Failed to decode AI JSON response: ' . json_last_error_msg() . ' - Raw: ' . $aiResponseContent);
                return response()->json(['error' => 'AI response was not in expected JSON format. Please try again or contact support.'], 500);
            }

            return response()->json($parsedResponse);

        } catch (\Exception $e) {
            Log::error('Prediction error: ' . $e->getMessage());
            Log::error('Prediction error trace: ' . $e->getTraceAsString());
            return response()->json(['error' => 'An internal server error occurred during prediction: ' . $e->getMessage()], 500);
        }
    }
}