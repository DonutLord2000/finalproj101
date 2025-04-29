<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class LumnixChatController extends Controller
{
  /**
   * Get user information for the chatbot.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function getUserInfo(Request $request)
  {
      try {
          $userId = null;
          $userName = "there";
          
          // Try multiple authentication methods
          if (Auth::check()) {
              $userId = Auth::id();
          } elseif ($request->user()) {
              $userId = $request->user()->id;
          } elseif (session()->has('user_id')) {
              $userId = session('user_id');
          }
          
          if ($userId) {
              $user = DB::table('users')->where('id', $userId)->first(['name']);
              if ($user && $user->name) {
                  $userName = $user->name;
              }
          }
          
          return response()->json([
              'name' => $userName,
              'authenticated' => !empty($userId)
          ]);
      } catch (\Exception $e) {
          Log::error('Error getting user info: ' . $e->getMessage());
          return response()->json([
              'name' => 'there',
              'authenticated' => false,
              'error' => 'Failed to retrieve user information'
          ]);
      }
  }

  /**
   * Handle the incoming chat request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function chat(Request $request)
  {
      try {
          // Validate request
          $validated = $request->validate([
              'message' => 'required|string',
              'sessionId' => 'required|string',
          ]);

          $message = $validated['message'];
          $sessionId = $validated['sessionId'];
          
          // Debug information
          $debugInfo = [];
          $debugInfo['auth_check'] = Auth::check() ? 'true' : 'false';
          $debugInfo['auth_id'] = Auth::check() ? Auth::id() : 'not authenticated';
          
          // Get user information - try multiple authentication methods
          $userData = null;
          $userId = null;
          
          // Try Laravel's Auth facade
          if (Auth::check()) {
              $userId = Auth::id();
              $debugInfo['auth_method'] = 'Auth facade';
          } 
          // Try request's user method
          elseif ($request->user()) {
              $userId = $request->user()->id;
              $debugInfo['auth_method'] = 'Request user';
          } 
          // Try session
          elseif (session()->has('user_id')) {
              $userId = session('user_id');
              $debugInfo['auth_method'] = 'Session';
          }
          
          $debugInfo['user_id'] = $userId;
          
          // Log debug information
          Log::info('Lumnix Chat Debug Info', $debugInfo);
          
          if ($userId) {
              $userData = $this->getUserData($userId);
              $debugInfo['user_data_retrieved'] = !empty($userData) ? 'true' : 'false';
              $debugInfo['user_name'] = $userData['name'] ?? 'not found';
              $debugInfo['experiences_count'] = count($userData['experiences'] ?? []);
              $debugInfo['education_count'] = count($userData['education'] ?? []);
              
              // Log the actual data for debugging
              Log::info('Lumnix User Data', [
                  'name' => $userData['name'] ?? 'not found',
                  'experiences' => $userData['experiences'] ?? [],
                  'education' => $userData['education'] ?? []
              ]);
          }

          // Check if the message is asking for analysis or comparison
          $needsAdditionalData = $this->messageNeedsAdditionalData($message);
          $additionalData = null;
          
          if ($needsAdditionalData && $userId) {
              $additionalData = $this->getTopData();
              $debugInfo['additional_data_retrieved'] = !empty($additionalData) ? 'true' : 'false';
              $debugInfo['top_experiences_count'] = count($additionalData['topExperiences'] ?? []);
              $debugInfo['top_education_count'] = count($additionalData['topEducation'] ?? []);
          }
          
          // Log updated debug information
          Log::info('Lumnix Chat Updated Debug Info', $debugInfo);

          // Get chat history from cache or create new
          $chatHistory = Cache::get('chat_history_' . $sessionId, []);

          // Prepare system message
          $systemMessage = $this->prepareSystemMessage($userData, $additionalData, $debugInfo);

          // Prepare messages for OpenAI
          $messages = [
              ['role' => 'system', 'content' => $systemMessage],
          ];

          // Add chat history (limited to last 10 messages to save tokens)
          $recentHistory = array_slice($chatHistory, -10);
          foreach ($recentHistory as $historyMessage) {
              $messages[] = $historyMessage;
          }

          // Add current user message
          $messages[] = ['role' => 'user', 'content' => $message];

          // Call OpenAI API
          $response = Http::withHeaders([
              'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
              'Content-Type' => 'application/json',
          ])->post('https://api.openai.com/v1/chat/completions', [
              'model' => 'gpt-4o-mini',
              'messages' => $messages,
              'temperature' => 0.7,
              'max_tokens' => 1000,
          ]);

          if ($response->failed()) {
              Log::error('OpenAI API error: ' . $response->body());
              return response()->json(['error' => 'Failed to get response from AI service'], 500);
          }

          $responseData = $response->json();
          $aiResponse = $responseData['choices'][0]['message']['content'];

          // Update chat history
          $chatHistory[] = ['role' => 'user', 'content' => $message];
          $chatHistory[] = ['role' => 'assistant', 'content' => $aiResponse];

          // Store updated chat history in cache (expires in 24 hours)
          Cache::put('chat_history_' . $sessionId, $chatHistory, 60 * 24);

          return response()->json(['response' => $aiResponse]);
      } catch (\Exception $e) {
          Log::error('Chat error: ' . $e->getMessage());
          Log::error('Chat error trace: ' . $e->getTraceAsString());
          return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
      }
  }

  /**
   * Get user data from database.
   *
   * @param int $userId
   * @return array
   */
  private function getUserData($userId)
  {
      try {
          // Log the query we're about to execute
          Log::info('Fetching user data for user ID: ' . $userId);
          
          // Get user name
          $user = DB::table('users')->where('id', $userId)->first(['name']);
          
          if (!$user) {
              Log::warning('User not found with ID: ' . $userId);
              return [
                  'name' => 'User',
                  'experiences' => [],
                  'education' => [],
              ];
          }
          
          // Log that we found the user
          Log::info('Found user: ' . $user->name);
          
          // Get experiences with specific columns
          $experiences = DB::table('experiences')
              ->where('user_id', $userId)
              ->select([
                  'title',
                  'company',
                  'employment_type',
                  'start_date',
                  'end_date',
                  'location',
                  'description'
              ])
              ->get();
          
          // Log experiences count
          Log::info('Found ' . count($experiences) . ' experiences for user ID: ' . $userId);
          
          // Get education with specific columns
          $education = DB::table('education')
              ->where('user_id', $userId)
              ->select([
                  'school',
                  'degree',
                  'field_of_study',
                  'start_date',
                  'end_date'
              ])
              ->get();
          
          // Log education count
          Log::info('Found ' . count($education) . ' education entries for user ID: ' . $userId);

          return [
              'name' => $user->name,
              'experiences' => $experiences,
              'education' => $education,
          ];
      } catch (\Exception $e) {
          Log::error('Error fetching user data: ' . $e->getMessage());
          Log::error('Error trace: ' . $e->getTraceAsString());
          
          // Try to get at least the user name
          try {
              $user = DB::table('users')->where('id', $userId)->first(['name']);
              return [
                  'name' => $user ? $user->name : 'User',
                  'experiences' => [],
                  'education' => [],
              ];
          } catch (\Exception $innerE) {
              Log::error('Error fetching user name: ' . $innerE->getMessage());
              return [
                  'name' => 'User',
                  'experiences' => [],
                  'education' => [],
              ];
          }
      }
  }

  /**
   * Get top 20 data for analysis.
   *
   * @return array
   */
  private function getTopData()
  {
      try {
          // Get top 20 experiences with specific columns
          $topExperiences = DB::table('experiences')
              ->select([
                  'title',
                  'company',
                  'employment_type',
                  'start_date',
                  'end_date',
                  'location',
                  'description'
              ])
              ->orderBy('created_at', 'desc')
              ->limit(20)
              ->get();
          
          // Log top experiences count
          Log::info('Found ' . count($topExperiences) . ' top experiences');

          // Get top 20 education entries with specific columns
          $topEducation = DB::table('education')
              ->select([
                  'school',
                  'degree',
                  'field_of_study',
                  'start_date',
                  'end_date'
              ])
              ->orderBy('created_at', 'desc')
              ->limit(20)
              ->get();
          
          // Log top education count
          Log::info('Found ' . count($topEducation) . ' top education entries');

          return [
              'topExperiences' => $topExperiences,
              'topEducation' => $topEducation,
          ];
      } catch (\Exception $e) {
          Log::error('Error fetching top data: ' . $e->getMessage());
          Log::error('Error trace: ' . $e->getTraceAsString());
          return [
              'topExperiences' => [],
              'topEducation' => [],
          ];
      }
  }

  /**
   * Check if the message is asking for analysis or comparison.
   *
   * @param string $message
   * @return bool
   */
  private function messageNeedsAdditionalData($message)
  {
      $keywords = [
          'compare', 'comparison', 'analyze', 'analysis', 'insight', 'insights',
          'career', 'trend', 'trends', 'statistics', 'stats', 'average',
          'others', 'other people', 'top', 'best', 'common'
      ];

      foreach ($keywords as $keyword) {
          if (stripos($message, $keyword) !== false) {
              return true;
          }
      }

      return false;
  }

  /**
   * Prepare system message for OpenAI.
   *
   * @param array|null $userData
   * @param array|null $additionalData
   * @param array $debugInfo
   * @return string
   */
  private function prepareSystemMessage($userData, $additionalData, $debugInfo = [])
  {
      $systemMessage = "You are Lumnix, a helpful career assistant chatbot. ";
      
      if ($userData && !empty($userData['name']) && $userData['name'] !== 'User') {
          $systemMessage .= "You are speaking with {$userData['name']}. ";
          
          if (!empty($userData['experiences'])) {
              $systemMessage .= "Here is their work experience: ";
              foreach ($userData['experiences'] as $experience) {
                  $period = "";
                  if (!empty($experience->start_date)) {
                      $period .= "from " . $experience->start_date;
                      if (!empty($experience->end_date)) {
                          $period .= " to " . $experience->end_date;
                      } else {
                          $period .= " to present";
                      }
                  }
                  
                  $systemMessage .= "{$experience->title} at {$experience->company}";
                  
                  if (!empty($experience->employment_type)) {
                      $systemMessage .= " ({$experience->employment_type})";
                  }
                  
                  if (!empty($period)) {
                      $systemMessage .= " {$period}";
                  }
                  
                  if (!empty($experience->location)) {
                      $systemMessage .= " in {$experience->location}";
                  }
                  
                  if (!empty($experience->description)) {
                      $systemMessage .= ". Description: {$experience->description}";
                  }
                  
                  $systemMessage .= ", ";
              }
              $systemMessage = rtrim($systemMessage, ", ") . ". ";
          }
          
          if (!empty($userData['education'])) {
              $systemMessage .= "Here is their education: ";
              foreach ($userData['education'] as $edu) {
                  $period = "";
                  if (!empty($edu->start_date)) {
                      $period .= "from " . $edu->start_date;
                      if (!empty($edu->end_date)) {
                          $period .= " to " . $edu->end_date;
                      } else {
                          $period .= " to present";
                      }
                  }
                  
                  $systemMessage .= "{$edu->degree}";
                  
                  if (!empty($edu->field_of_study)) {
                      $systemMessage .= " in {$edu->field_of_study}";
                  }
                  
                  if (!empty($edu->school)) {
                      $systemMessage .= " from {$edu->school}";
                  }
                  
                  if (!empty($period)) {
                      $systemMessage .= " {$period}";
                  }
                  
                  $systemMessage .= ", ";
              }
              $systemMessage = rtrim($systemMessage, ", ") . ". ";
          }
      } else {
          // If we couldn't get user data, let the AI know
          $systemMessage .= "You are speaking with a user whose profile data couldn't be retrieved. ";
          $systemMessage .= "You should acknowledge this and ask them for relevant information about their career and education to provide personalized advice. ";
      }

      if ($additionalData && (!empty($additionalData['topExperiences']) || !empty($additionalData['topEducation']))) {
          $systemMessage .= "You have access to data about other users for comparison: ";
          
          if (!empty($additionalData['topExperiences'])) {
              $systemMessage .= "Top experiences include: ";
              foreach ($additionalData['topExperiences'] as $exp) {
                  $systemMessage .= "{$exp->title} at {$exp->company}";
                  
                  if (!empty($exp->employment_type)) {
                      $systemMessage .= " ({$exp->employment_type})";
                  }
                  
                  if (!empty($exp->location)) {
                      $systemMessage .= " in {$exp->location}";
                  }
                  
                  $systemMessage .= ", ";
              }
              $systemMessage = rtrim($systemMessage, ", ") . ". ";
          }
          
          if (!empty($additionalData['topEducation'])) {
              $systemMessage .= "Top education includes: ";
              foreach ($additionalData['topEducation'] as $edu) {
                  $systemMessage .= "{$edu->degree}";
                  
                  if (!empty($edu->field_of_study)) {
                      $systemMessage .= " in {$edu->field_of_study}";
                  }
                  
                  if (!empty($edu->school)) {
                      $systemMessage .= " from {$edu->school}";
                  }
                  
                  $systemMessage .= ", ";
              }
              $systemMessage = rtrim($systemMessage, ", ") . ". ";
          }
      } else if ($additionalData) {
          // If we tried to get additional data but couldn't find any
          $systemMessage .= "You attempted to retrieve comparison data from other users, but none was found. ";
      }

      $systemMessage .= "Provide helpful career advice and insights based on this information. Be concise, friendly, and professional. ";
      $systemMessage .= "If you don't have the user's data, acknowledge this and ask them to share relevant information about their career and education to provide personalized advice.";
      
      return $systemMessage;
  }

  /**
   * Clear chat history when user logs out.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function clearChatHistory(Request $request)
  {
      try {
          $sessionId = $request->input('sessionId');
          
          if ($sessionId) {
              // Clear the chat history from cache
              Cache::forget('chat_history_' . $sessionId);
              
              return response()->json([
                  'success' => true,
                  'message' => 'Chat history cleared successfully'
              ]);
          }
          
          return response()->json([
              'success' => false,
              'message' => 'Session ID is required'
          ], 400);
      } catch (\Exception $e) {
          Log::error('Error clearing chat history: ' . $e->getMessage());
          return response()->json([
              'success' => false,
              'error' => 'Failed to clear chat history'
          ], 500);
      }
  }
}
