<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use App\Models\Comment;
use App\Models\Reaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Services\ContentModerationService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth; // Import Auth facade

class ThreadController extends Controller
{
    protected $moderationService;

    public function __construct(ContentModerationService $moderationService)
    {
        $this->moderationService = $moderationService;
    }

    public function index(Request $request)
    {
        $query = Thread::with('user')
            ->withCount([
                'comments', // This will add comments_count
                'reactions as upvotes_count' => function ($query) {
                    $query->where('type', 'upvote');
                },
                'reactions as hearts_count' => function ($query) {
                    $query->where('type', 'heart');
                },
            ]);

        // Improved search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                // Use LIKE with wildcards for partial matching
                $q->whereRaw('LOWER(content) LIKE ?', ['%' . strtolower($search) . '%'])
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%']);
                  })
                  ->orWhere(function($q2) use ($search) { // Search anonymous posts
                      $q2->where('is_anonymous', true)
                         ->whereRaw('LOWER(content) LIKE ?', ['%' . strtolower($search) . '%']);
                  });
            });
        }

        // Role filter
        if ($request->has('role') && $request->role !== 'all') {
            if ($request->role === 'guest') {
                $query->where('is_anonymous', true);
            } else {
                $query->where('is_anonymous', false) // Only consider non-anonymous for specific roles
                      ->whereHas('user', function($q) use ($request) {
                          $q->where('role', $request->role);
                      });
            }
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
                    $query->orderByDesc('upvotes_count'); // This will now work correctly
                    break;
                case 'most_heart':
                    $query->orderByDesc('hearts_count'); // This will now work correctly
                    break;
                case 'most_comment':
                    $query->orderByDesc('comments_count'); // This already worked
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
        $thread->load(['user', 'comments.user', 'comments.editor', 'comments.deleter']);
        $thread->loadCount([
            'comments', // This will add comments_count
            'reactions as upvotes_count' => function ($query) {
                $query->where('type', 'upvote');
            },
            'reactions as hearts_count' => function ($query) {
                $query->where('type', 'heart');
            },
        ]);

        return view('threads.show', compact('thread'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|max:255',
            'content' => 'required|max:10000',
            'is_anonymous' => 'boolean', // Validate the new field
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

        $isAnonymous = $request->input('is_anonymous', false); // Get value, default to false

        $threadData = [
            'content' => $validated['content'],
            'is_anonymous' => $isAnonymous,
        ];

        if ($isAnonymous) {
            $threadData['user_id'] = null; // Set user_id to null for anonymous posts
            $threadData['title'] = 'Anonymous Post'; // Default title for anonymous
        } else {
            // Ensure user is authenticated for non-anonymous posts
            if (!Auth::check()) {
                return response()->json(['message' => 'You must be logged in to post a non-anonymous thread.'], 403);
            }
            $threadData['user_id'] = Auth::id(); // Link to authenticated user
            $threadData['title'] = $validated['title'] ?? Auth::user()->name; // Use provided title or user's name
        }

        $thread = Thread::create($threadData);

        Log::info('Thread created successfully', ['thread_id' => $thread->id, 'is_anonymous' => $isAnonymous]);

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
        // Anonymous users cannot react
        if (!Auth::check()) {
            return response()->json(['message' => 'You must be logged in to react.'], 403);
        }

        $type = $request->input('type');
        $user = Auth::user();

        // Find the existing reaction by the user for the given type
        $reaction = $thread->reactions()->where('user_id', $user->id)->where('type', $type)->first();

        // Toggle reaction
        if ($reaction) {
            $reaction->delete();
            $message = 'Reaction removed';
        } else {
            $thread->reactions()->create([
                'user_id' => $user->id,
                'type' => $type,
            ]);
            $message = 'Reaction added';
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
            'is_anonymous' => 'boolean', // Validate the new field
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

        $isAnonymous = $request->input('is_anonymous', false);

        $commentData = [
            'thread_id' => $thread->id,
            'content' => $validated['content'],
            'is_anonymous' => $isAnonymous,
        ];

        if ($isAnonymous) {
            $commentData['user_id'] = null; // Set user_id to null for anonymous comments
        } else {
            // Only allow authenticated users to post non-anonymous comments
            if (!Auth::check()) {
                return response()->json(['message' => 'You must be logged in to post a non-anonymous comment.'], 403);
            }
            $commentData['user_id'] = Auth::id();
        }

        $comment = Comment::create($commentData);

        Log::info('Comment created successfully', ['comment_id' => $comment->id, 'is_anonymous' => $isAnonymous]);

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
        // If not authenticated, no reactions are active
        if (!Auth::check()) {
            return response()->json([
                'upvote' => false,
                'heart' => false,
            ]);
        }
        $user = Auth::user();
        return response()->json([
            'upvote' => $thread->reactions()->where('user_id', $user->id)->where('type', 'upvote')->exists(),
            'heart' => $thread->reactions()->where('user_id', $user->id)->where('type', 'heart')->exists(),
        ]);
    }

    public function edit(Thread $thread)
    {
        // Only allow editing if not anonymous or if user is admin
        if ($thread->is_anonymous && Auth::check() && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }
        $this->authorize('update', $thread);
        return view('threads.edit', compact('thread'));
    }

    public function update(Request $request, Thread $thread)
    {
        // Only allow updating if not anonymous or if user is admin
        if ($thread->is_anonymous && Auth::check() && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }
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
        // Only allow deleting if not anonymous or if user is admin
        if ($thread->is_anonymous && Auth::check() && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }
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
