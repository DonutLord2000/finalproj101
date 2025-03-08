<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        $message = $request->input('message');
        $history = $request->input('history', []);
        
        // Prepare the base messages array
        $messages = [
            ['role' => 'system', 'content' => 'You are a helpful assistant.'],
        ];

        // Limit history to a manageable size for context analysis
        $historyContext = array_slice($history, -5);  // Analyze the last 5 messages

        // Analyze if history is needed
        $contextMessage = "The user has previously said: ";
        foreach ($historyContext as $item) {
            $contextMessage .= "\n- " . $item['content'];
        }
        
        // Now ask the model if history is necessary to include
        $response = OpenAI::chat()->create([
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'system', 'content' => 'You are an assistant that decides if previous conversation history is necessary.'],
                ['role' => 'user', 'content' => "Based on the following, do we need to include the conversation history in the current chat prompt? \n\n$contextMessage"],
            ],
        ]);

        // Get the model's response to determine if the history should be included
        $includeHistory = strpos(strtolower($response->choices[0]->message->content), 'yes') !== false;

        // Add history to the messages if needed
        if ($includeHistory) {
            foreach ($historyContext as $item) {
                $messages[] = ['role' => $item['role'], 'content' => $item['content']];
            }
        }

        // Add the current user message
        $messages[] = ['role' => 'user', 'content' => $message];

        // Call the OpenAI API to generate the response
        $response = OpenAI::chat()->create([
            'model' => 'gpt-4o-mini',
            'messages' => $messages,
        ]);

        // Calculate total tokens used
        $totalTokens = $response->usage->promptTokens + $response->usage->completionTokens;

        // Log token usage
        Log::info('ChatGPT API Token Usage', [
            'prompt_tokens' => $response->usage->promptTokens,
            'completion_tokens' => $response->usage->completionTokens,
            'total_tokens' => $totalTokens,
            'user_id' => $request->user() ? $request->user()->id : 'guest', // Log user ID if authenticated
            'timestamp' => now()->toDateTimeString(),
        ]);

        return response()->json([
            'message' => $response->choices[0]->message->content,
        ]);
    }
}
