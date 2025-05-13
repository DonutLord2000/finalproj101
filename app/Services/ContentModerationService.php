<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ContentModerationService
{
    protected $apiKey;
    
    public function __construct()
    {
        $this->apiKey = env('OPENAI_API_KEY');
    }
    
    public function moderateContent($content)
    {
        try {
            Log::info('Starting content moderation check');
            
            // Check for common profanity first (faster than API call)
            $profanityResult = $this->checkCommonProfanity($content);
            if (!$profanityResult['safe']) {
                Log::warning('Content contains common profanity', ['reason' => $profanityResult['message']]);
                return $profanityResult;
            }
            
            // If no common profanity found, proceed with OpenAI check
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a content moderation assistant. Your task is to determine if the provided content contains toxicity, harmful intentions, hate speech, or inappropriate content. Respond with only "SAFE" if the content is appropriate, or "UNSAFE: [reason]" if the content is inappropriate, where [reason] is a brief explanation of why the content is inappropriate.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $content
                    ]
                ],
                'temperature' => 0.3,
                'max_tokens' => 150
            ]);
            
            $result = $response->json();
            Log::info('OpenAI API response', ['response' => $result]);
            
            if (isset($result['choices'][0]['message']['content'])) {
                $moderationResult = $result['choices'][0]['message']['content'];
                
                if (strpos($moderationResult, 'SAFE') === 0) {
                    Log::info('Content deemed safe');
                    return [
                        'safe' => true,
                        'message' => null
                    ];
                } else {
                    // Extract the reason from the "UNSAFE: [reason]" format
                    $reason = str_replace('UNSAFE:', '', $moderationResult);
                    Log::warning('Content deemed unsafe', ['reason' => $reason]);
                    return [
                        'safe' => false,
                        'message' => trim($reason)
                    ];
                }
            }
            
            // If we can't parse the response, log it and default to safe
            Log::warning('Unable to parse OpenAI moderation response', ['response' => $result]);
            return [
                'safe' => true,
                'message' => null
            ];
            
        } catch (\Exception $e) {
            Log::error('Error in content moderation service', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // If there's an error, default to safe to avoid blocking legitimate content
            return [
                'safe' => true,
                'message' => null
            ];
        }
    }
    
    /**
     * Check for common profanity words to avoid unnecessary API calls
     */
    private function checkCommonProfanity($content)
    {
        // Convert to lowercase for case-insensitive matching
        $lowercaseContent = strtolower($content);
        
        // List of common profanity words to check
        $profanityWords = [
            'fuck', 'shit', 'ass', 'bitch', 'cunt', 'dick', 'pussy', 'cock', 
            'whore', 'slut', 'bastard', 'asshole', 'motherfucker', 'bullshit',
            'damn', 'hell', 'piss', 'crap'
        ];
        
        foreach ($profanityWords as $word) {
            // Check for whole word matches using word boundaries
            if (preg_match('/\b' . preg_quote($word, '/') . '\b/', $lowercaseContent)) {
                return [
                    'safe' => false,
                    'message' => 'Contains profanity.'
                ];
            }
        }
        
        return [
            'safe' => true,
            'message' => null
        ];
    }
}
