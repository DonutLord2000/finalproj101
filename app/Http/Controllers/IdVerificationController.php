<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IdVerificationController extends Controller
{
    public function verifyId(Request $request)
    {
        $request->validate([
            'idNumber' => 'required|string|max:255',
            'imageData' => 'required|string', // Base64 encoded image
        ]);

        $idNumber = $request->input('idNumber');
        $imageData = $request->input('imageData'); // This will be a base64 string like "data:image/png;base64,..."

        // Remove the "data:image/jpeg;base64," prefix if present, as OpenAI expects just the base64 string
        $base64Image = preg_replace('/^data:image\/(png|jpeg|jpg|gif);base64,/', '', $imageData);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o', // Using gpt-4o for vision capabilities
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => [
                            ['type' => 'text', 'text' => 'Analyze the provided image of an ID card.
                            1. Determine if it is an official ID from "GRC - Global Reciprocal College" or "T.I.P. - Technological Institute of the Philippines".
                            2. If it is from either of these institutions, extract the primary ID number clearly visible on the card.
                            3. If it is not from a recognized institution, or if no ID number can be clearly extracted, state that.

                            Provide your response in a JSON format with the following structure:
                            {
                              "isRecognizedID": boolean,
                              "institution": "GRC" | "TIP" | null, // "GRC" if Global Reciprocal College, "TIP" if Technological Institute of the Philippines, null otherwise
                              "extractedID": string | null,
                              "message": string
                            }

                            Example for GRC ID:
                            {
                              "isRecognizedID": true,
                              "institution": "GRC",
                              "extractedID": "GRC-12345",
                              "message": "ID is from GRC and ID number extracted successfully."
                            }

                            Example for TIP ID:
                            {
                              "isRecognizedID": true,
                              "institution": "TIP",
                              "extractedID": "TIP-67890",
                              "message": "ID is from TIP and ID number extracted successfully."
                            }

                            Example for Non-Recognized ID:
                            {
                              "isRecognizedID": false,
                              "institution": null,
                              "extractedID": null,
                              "message": "This does not appear to be an ID from GRC or TIP."
                            }

                            Example for Recognized ID with unreadable number:
                            {
                              "isRecognizedID": true,
                              "institution": "GRC", // or "TIP"
                              "extractedID": null,
                              "message": "ID from GRC detected, but ID number could not be clearly extracted."
                            }'],
                            [
                                'type' => 'image_url',
                                'image_url' => [
                                    'url' => 'data:image/jpeg;base64,' . $base64Image, // Re-add prefix for OpenAI
                                ],
                            ],
                        ],
                    ],
                ],
                'response_format' => ['type' => 'json_object'],
                'max_tokens' => 300, // Limit response length
            ]);

            $openAiResponse = $response->json();

            if (isset($openAiResponse['choices'][0]['message']['content'])) {
                $content = $openAiResponse['choices'][0]['message']['content'];
                $result = json_decode($content, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    Log::error('Failed to decode OpenAI JSON response: ' . json_last_error_msg() . ' Content: ' . $content);
                    return response()->json(['isValid' => false, 'message' => 'Failed to parse AI response.'], 500);
                }

                $isRecognizedID = $result['isRecognizedID'] ?? false;
                $institution = $result['institution'] ?? null;
                $extractedID = $result['extractedID'] ?? null;
                $aiMessage = $result['message'] ?? 'No specific message from AI.';

                $isMatch = $isRecognizedID && ($extractedID === $idNumber);

                if ($isMatch) {
                    return response()->json([
                        'isValid' => true,
                        'message' => "ID verified successfully! ID from {$institution} and number match.",
                        'extractedID' => $extractedID,
                    ]);
                } else {
                    $message = "ID verification failed.";
                    if (!$isRecognizedID) {
                        $message = "This does not appear to be an ID from GRC or TIP. Please upload a valid ID.";
                    } elseif ($isRecognizedID && $extractedID === null) {
                        $message = "ID from {$institution} detected, but the ID number could not be clearly read. Please ensure the ID number is visible.";
                    } elseif ($isRecognizedID && $extractedID !== $idNumber) {
                        $message = "ID from {$institution} detected, but the extracted ID number ({$extractedID}) does not match the entered ID ({$idNumber}). Please check your input.";
                    }
                    return response()->json(['isValid' => false, 'message' => $message]);
                }
            } else {
                Log::error('OpenAI response missing content: ' . json_encode($openAiResponse));
                return response()->json(['isValid' => false, 'message' => 'Unexpected response from AI service.'], 500);
            }

        } catch (\Exception $e) {
            Log::error('Error during ID verification with OpenAI: ' . $e->getMessage());
            return response()->json(['isValid' => false, 'message' => 'An error occurred during ID verification.'], 500);
        }
    }
}
