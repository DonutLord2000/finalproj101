<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class chat extends Controller
{
public function chat(Request $request)
{
    $request->validate([
        'message' => 'required|string',
    ]);

    try {
        $currentUser = Auth::user();
        if (!$currentUser) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Base context for the system message
        $context = [
            'current_user' => [
                'name' => $currentUser->name,
                'bio' => $currentUser->bio ?? 'No bio provided.',
                'jobs' => $currentUser->jobs ?? 'No jobs listed.',
                'achievements' => $currentUser->achievements ?? 'No achievements provided.',
            ],
        ];

        // Check if the user explicitly asks for alumni data
        $userMessage = $request->input('message');
        $includeAllUsers = str_contains(strtolower($userMessage), 'alumni') || str_contains(strtolower($userMessage), 'users');

        if ($includeAllUsers) {
            // Fetch all user data on-demand
            $cacheKey = 'user_data_' . $currentUser->id;
            $allUserData = Cache::remember($cacheKey, 600, function () {
                return DB::table('users')
                    ->select('name', 'jobs', 'achievements', 'role', 'is_employed')
                    ->get();
            });

            $context['all_users'] = $allUserData;
        }

        // Construct the system message
        $systemMessage = 'You are AI-Lumni, a helpful assistant. Respond based on the following information: ' . json_encode($context);

        // Call OpenAI API
        $result = OpenAI::chat()->create([
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'system', 'content' => $systemMessage],
                ['role' => 'user', 'content' => $userMessage],
            ],
            'max_tokens' => 200,
        ]);

        // Log token usage
        $tokensUsed = $result['usage']['total_tokens'] ?? 0;
        Log::info('OpenAI Token Usage', [
            'user_id' => $currentUser->id,
            'tokens_used' => $tokensUsed,
            'request_message' => $userMessage,
            'response_message' => $result['choices'][0]['message']['content'] ?? 'No response content',
        ]);

        // Parse response
        $reply = $result['choices'][0]['message']['content'] ?? 'Sorry, I could not generate a response.';
    } catch (\Exception $e) {
        $reply = 'An error occurred: ' . $e->getMessage();
    }

    return response()->json([
        'reply' => $reply,
    ], 200);
}
}