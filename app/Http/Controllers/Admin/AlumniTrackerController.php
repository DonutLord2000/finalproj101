<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumnus;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; // Import DB facade for raw queries
use Illuminate\Http\Request;
// Removed Carbon as it's no longer needed for age calculation

class AlumniTrackerController extends Controller
{
    /**
     * Get unique filter options from the alumni table.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFilterOptions(Request $request)
    {
        $years = Alumnus::distinct()->pluck('year_graduated')->filter()->sort()->values();
        $degreePrograms = Alumnus::distinct()->pluck('degree_program')->filter()->sort()->values();
        $industries = Alumnus::distinct()->pluck('industry')->filter()->sort()->values();
        $jobTitles = Alumnus::distinct()->pluck('job_title')->filter()->sort()->values();
        $employmentStatuses = Alumnus::distinct()->pluck('employment_status')->filter()->sort()->values();
        $genders = Alumnus::distinct()->pluck('gender')->filter()->sort()->values();

        return response()->json([
            'years' => $years,
            'degreePrograms' => $degreePrograms,
            'industries' => $industries,
            'jobTitles' => $jobTitles,
            'employmentStatuses' => $employmentStatuses,
            'genders' => $genders,
        ]);
    }

    /**
     * Search alumni by name for autocomplete.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchAlumni(Request $request)
    {
        $query = $request->input('query');

        if (empty($query)) {
            return response()->json([]);
        }

        $alumni = Alumnus::select('id', 'name')
                        ->where('name', 'like', '%' . $query . '%')
                        ->limit(10)
                        ->get();

        return response()->json($alumni);
    }

    /**
     * Get alumni insights based on filters or individual alumni ID.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAlumniInsights(Request $request)
    {
        $alumniId = $request->input('alumni_id');
        $alumniQuery = Alumnus::query();

        Log::info('AlumniTrackerController: getAlumniInsights called.');
        Log::info('Request parameters: ' . json_encode($request->all()));

        if ($alumniId) {
            $alumniQuery->where('id', $alumniId);
            Log::info('Individual search requested for alumni ID: ' . $alumniId);
        } else {
            // Apply filters for group analysis
            Log::info('Group analysis requested with filters.');
            if ($request->filled('from_year')) {
                $alumniQuery->where('year_graduated', '>=', $request->input('from_year'));
            }
            if ($request->filled('to_year')) {
                $alumniQuery->where('year_graduated', '<=', $request->input('to_year'));
            }
            if ($request->filled('degree_program')) {
                $alumniQuery->where('degree_program', $request->input('degree_program'));
            }
            if ($request->filled('industry')) {
                $alumniQuery->where('industry', $request->input('industry'));
            }
            if ($request->filled('job_title')) {
                $alumniQuery->where('job_title', $request->input('job_title'));
            }
            if ($request->filled('employment_status')) {
                $alumniQuery->where('employment_status', $request->input('employment_status'));
            }
            if ($request->filled('gender')) {
                $alumniQuery->where('gender', $request->input('gender'));
            }
            if ($request->filled('age_from')) {
                // Now directly using 'age' column
                $alumniQuery->where('age', '>=', $request->input('age_from'));
            }
            if ($request->filled('age_to')) {
                // Now directly using 'age' column
                $alumniQuery->where('age', '<=', $request->input('age_to'));
            }
            if ($request->filled('salary_from')) {
                $alumniQuery->where('monthly_salary', '>=', $request->input('salary_from'));
            }
            if ($request->filled('salary_to')) {
                $alumniQuery->where('monthly_salary', '<=', $request->input('salary_to'));
            }
        }

        $alumniData = $alumniQuery->get([
            'name', 'year_graduated', 'degree_program', 'major', 'minor',
            'employment_status', 'job_title', 'company', 'industry', 'nature_of_work', 'monthly_salary', 'gender', 'age' // Changed from date_of_birth to age
        ]);

        if ($alumniData->isEmpty()) {
            Log::warning('No alumni found matching criteria. Alumni ID: ' . $alumniId . ', Filters: ' . json_encode($request->all()));
            return response()->json(['insight' => 'No alumni found matching the criteria.'], 404);
        }

        $promptData = $alumniData->map(function ($alumnus) {
            // Directly use the 'age' column value
            $age = $alumnus->age; 
            return [
                'name' => $alumnus->name,
                'year_graduated' => $alumnus->year_graduated,
                'degree_program' => $alumnus->degree_program,
                'major' => $alumnus->major,
                'employment_status' => $alumnus->employment_status,
                'job_title' => $alumnus->job_title,
                'company' => $alumnus->company,
                'industry' => $alumnus->industry,
                'monthly_salary' => $alumnus->monthly_salary,
                'gender' => $alumnus->gender,
                'age' => $age, // Directly use age
            ];
        })->toJson(JSON_PRETTY_PRINT);

        $systemPrompt = 'You are an expert alumni data analyst. Provide insightful and concise summaries.';
        $userPrompt = '';
        $responseFormat = ['type' => 'json_object'];

        if ($alumniId) {
            $userPrompt = "Provide a detailed profile summary for the following alumnus in JSON format. Focus on their educational background, career progression, and key achievements. Include their name, a brief summary, education details, career details, and a list of key achievements. Ensure the monthly salary is formatted as a number. The JSON structure should be:\n" .
                          "{\n" .
                          "  \"name\": \"[Alumnus Name]\",\n" .
                          "  \"summary\": \"[Brief overall summary]\",\n" .
                          "  \"education\": {\n" .
                          "    \"degree\": \"[Degree]\",\n" .
                          "    \"major\": \"[Major]\",\n" .
                          "    \"graduation_year\": [Year]\n" .
                          "  },\n" .
                          "  \"career_details\": {\n" .
                          "    \"company\": \"[Company]\",\n" .
                          "    \"job_title\": \"[Job Title]\",\n" .
                          "    \"industry\": \"[Industry]\",\n" .
                          "    \"employment_status\": \"[Employment Status]\",\n" .
                          "    \"monthly_salary\": [Number] // e.g., 22000\n" .
                          "  },\n" .
                          "  \"key_achievements\": [\"[Achievement 1]\", \"[Achievement 2]\", ...],\n" .
                          "  \"overall_impression\": \"[Optional concluding remark]\"\n" .
                          "}\n\n" .
                          "Alumnus Data:\n" . $promptData;
            Log::info('OpenAI prompt for individual (structured): ' . $userPrompt);
        } else {
            $userPrompt = "Analyze the following alumni data and provide comprehensive insights in JSON format. Focus on career progression, common industries/job titles for their degrees, and employment trends. Include a general summary, key trends as bullet points, and data for charts. Ensure all percentages sum to 100% where applicable. The JSON structure should be:\n" .
                          "{\n" .
                          "  \"summary\": \"[General summary paragraph]\",\n" .
                          "  \"key_trends\": [\"[Trend 1]\", \"[Trend 2]\", ...],\n" .
                          "  \"employment_status_distribution\": {\"Status1\": Percentage, \"Status2\": Percentage, ...},\n" .
                          "  \"top_industries\": [{\"industry\": \"[Name]\", \"count\": [Number]}, ...],\n" .
                          "  \"top_degree_programs\": [{\"program\": \"[Name]\", \"count\": [Number]}, ...],\n" .
                          "  \"career_progression_notes\": \"[Optional notes on career progression]\"\n" .
                          "}\n\n" .
                          "Alumni Data:\n" . $promptData;
            Log::info('OpenAI prompt for group: ' . $userPrompt);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt],
                ],
                'max_tokens' => 1000,
                'response_format' => $responseFormat,
            ])->json();

            Log::info('OpenAI raw response: ' . json_encode($response));

            $insightContent = $response['choices'][0]['message']['content'] ?? null;

            if (!$insightContent) {
                Log::error('OpenAI did not return content for insight.');
                return response()->json(['insight' => 'Could not generate insight. OpenAI returned empty content.'], 500);
            }

            $parsedInsight = json_decode($insightContent, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($parsedInsight)) {
                Log::info('Insight JSON parsed successfully.');
                return response()->json($parsedInsight);
            } else {
                Log::error('OpenAI returned invalid JSON: ' . $insightContent);
                return response()->json(['insight' => 'Error: Could not parse AI insights. Please try again or refine your filters.'], 500);
            }

        } catch (\Exception $e) {
            Log::error('Error generating AI insight: ' . $e->getMessage());
            return response()->json(['insight' => 'Error generating AI insight: ' . $e->getMessage()], 500);
        }
    }
}
