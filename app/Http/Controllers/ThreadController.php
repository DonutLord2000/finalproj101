<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use App\Models\Comment;
use App\Models\Reaction; 
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Services\ContentModerationService;
use Illuminate\Support\Facades\Log;

class ThreadController extends Controller
{
    protected $moderationService;
    
    public function __construct(ContentModerationService $moderationService)
    {
        $this->moderationService = $moderationService;
    }
    
    public function index(Request $request)
    {
        $query = Thread::withCount(['comments', 'upvotes', 'hearts'])
            ->with('user');
        
        // Improved search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                // Use LIKE with wildcards for partial matching
                $q->whereRaw('LOWER(content) LIKE ?', ['%' . strtolower($search) . '%'])
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%']);
                  });
            });
        }
        
        // Role filter
        if ($request->has('role') && $request->role !== 'all') {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('role', $request->role);
            });
        }
        
        // Time filter
        if ($request->has('time')) {
            switch ($request->time) {
                case 'today':
                    $query->whereDate('created_at', Carbon::today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
                    break;
            }
        }
        
        // Sort filter
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'most_liked':
                    $query->orderByDesc('upvotes_count');
                    break;
                case 'most_heart':
                    $query->orderByDesc('hearts_count');
                    break;
                case 'most_comment':
                    $query->orderByDesc('comments_count');
                    break;
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }
        
        $threads = $query->paginate(perPage: 20)->withQueryString();
        
        return view('threads.index', compact('threads'));
    }

    public function show(Thread $thread)
    {
        $thread->load('user', 'comments.user');
        $thread->loadCount(['comments', 'upvotes', 'hearts']);

        return view('threads.show', compact('thread'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|max:255', // Make title optional
            'content' => 'required|max:10000', // Add max length validation
        ]);
        
        // Count words and validate maximum words
        $wordCount = str_word_count($validated['content']);
        if ($wordCount > 700) {
            return response()->json([
                'success' => false,
                'message' => 'Content exceeds the maximum limit of 700 words.'
            ], 422);
        }
        
        // Check content with OpenAI moderation
        Log::info('Checking thread content with moderation service', ['content_length' => strlen($validated['content'])]);
        $moderationResult = $this->moderationService->moderateContent($validated['content']);
        
        if (!$moderationResult['safe']) {
            Log::warning('Thread content failed moderation check', ['reason' => $moderationResult['message']]);
            return response()->json([
                'success' => false,
                'message' => 'Your post contains inappropriate content: ' . $moderationResult['message']
            ], 422);
        }
    
        // Set default title as user's name if title is not provided
        $validated['title'] = $validated['title'] ?? auth()->user()->name;
    
        $thread = auth()->user()->threads()->create($validated);
        
        Log::info('Thread created successfully', ['thread_id' => $thread->id]);
    
        // Check if request wants JSON
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Thread created successfully',
                'redirect' => route('threads.show', $thread)
            ]);
        }
        
        // Regular form submission fallback
        return redirect()->route('threads.show', $thread);
    }

    public function react(Request $request, Thread $thread)
    {
        $type = $request->input('type');
        $user = auth()->user();

        // Find the existing reaction by the user for the given type
        $reaction = $thread->reactions()->where('user_id', $user->id)->where('type', $type)->first();

        // Toggle reaction
        if ($reaction) {
            $reaction->delete();
            $message = 'Reaction removed';
            $userReacted = false; // User no longer has this reaction
        } else {
            $thread->reactions()->create([
                'user_id' => $user->id,
                'type' => $type,
            ]);
            $message = 'Reaction added';
            $userReacted = true; // User now has this reaction
        }

        // Recalculate the counts for each reaction type
        $counts = [
            'upvotes' => $thread->reactions()->where('type', 'upvote')->count(),
            'hearts' => $thread->reactions()->where('type', 'heart')->count(),
        ];

        return response()->json([
            'message' => $message,
            'counts' => $counts,
            'userReacted' => [
                'upvote' => $thread->reactions()->where('user_id', $user->id)->where('type', 'upvote')->exists(),
                'heart' => $thread->reactions()->where('user_id', $user->id)->where('type', 'heart')->exists(),
            ]
        ]);
    }

    public function storeComment(Request $request, Thread $thread)
    {
        $validated = $request->validate([
            'content' => 'required|max:10000',
        ]);
        
        // Count words and validate maximum words
        $wordCount = str_word_count($validated['content']);
        if ($wordCount > 700) {
            return response()->json([
                'success' => false,
                'message' => 'Comment exceeds the maximum limit of 700 words.'
            ], 422);
        }
        
        // Check content with OpenAI moderation
        Log::info('Checking comment content with moderation service', ['content_length' => strlen($validated['content'])]);
        $moderationResult = $this->moderationService->moderateContent($validated['content']);
        
        if (!$moderationResult['safe']) {
            Log::warning('Comment content failed moderation check', ['reason' => $moderationResult['message']]);
            return response()->json([
                'success' => false,
                'message' => 'Your comment contains inappropriate content: ' . $moderationResult['message']
            ], 422);
        }

        $comment = $thread->comments()->create([
            'user_id' => auth()->id(),
            'content' => $validated['content'],
        ]);
        
        Log::info('Comment created successfully', ['comment_id' => $comment->id]);

        // Check if request wants JSON
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Comment added successfully',
                'redirect' => route('threads.show', $thread->id)
            ]);
        }
        
        // Regular form submission fallback
        return redirect()->route('threads.show', $thread);
    }

    public function getReactionStatus(Thread $thread)
    {
        $user = auth()->user();
        return response()->json([
            'upvote' => $thread->reactions()->where('user_id', $user->id)->where('type', 'upvote')->exists(),
            'heart' => $thread->reactions()->where('user_id', $user->id)->where('type', 'heart')->exists(),
        ]);
    }

    public function edit(Thread $thread)
    {
        $this->authorize('update', $thread);
        return view('threads.edit', compact('thread'));
    }

    public function update(Request $request, Thread $thread)
    {
        $this->authorize('update', $thread);
        
        $validated = $request->validate([
            'content' => 'required|max:10000',
        ]);
        
        // Count words and validate maximum words
        $wordCount = str_word_count($validated['content']);
        if ($wordCount > 700) {
            return response()->json([
                'success' => false,
                'message' => 'Content exceeds the maximum limit of 700 words.'
            ], 422);
        }
        
        // Check content with OpenAI moderation
        Log::info('Checking thread update content with moderation service');
        $moderationResult = $this->moderationService->moderateContent($validated['content']);
        
        if (!$moderationResult['safe']) {
            Log::warning('Thread update content failed moderation check', ['reason' => $moderationResult['message']]);
            return response()->json([
                'success' => false,
                'message' => 'Your post contains inappropriate content: ' . $moderationResult['message']
            ], 422);
        }

        $thread->update($validated);
        
        Log::info('Thread updated successfully', ['thread_id' => $thread->id]);

        return response()->json([
            'success' => true,
            'message' => 'Thread updated successfully',
            'redirect' => route('threads.show', $thread)
        ]);
    }

    public function destroy(Thread $thread)
    {
        $this->authorize('delete', $thread);
        
        $thread->delete();

        return redirect()->route('threads.index');
    }
    
    public function checkContent(Request $request)
    {
        $content = $request->input('content');
        
        // Count words
        $wordCount = str_word_count($content);
        $isOverLimit = $wordCount > 700;
        
        // Only check moderation if content is substantial
        $moderationResult = ['safe' => true, 'message' => null];
        if (strlen($content) > 10) {
            Log::info('Checking content via API endpoint', ['content_length' => strlen($content)]);
            $moderationResult = $this->moderationService->moderateContent($content);
        }
        
        return response()->json([
            'wordCount' => $wordCount,
            'isOverLimit' => $isOverLimit,
            'isSafe' => $moderationResult['safe'],
            'moderationMessage' => $moderationResult['message']
        ]);
    }
}
