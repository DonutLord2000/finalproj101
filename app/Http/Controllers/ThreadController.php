<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use App\Models\Comment;
use App\Models\Reaction; 
use Illuminate\Http\Request;
use Carbon\Carbon;

class ThreadController extends Controller
{
    public function index(Request $request)
    {
        $query = Thread::withCount(['comments', 'upvotes', 'hearts'])
            ->with('user');
        
        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('content', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
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
            'content' => 'required',
        ]);
    
        // Set default title as user's name if title is not provided
        $validated['title'] = $validated['title'] ?? auth()->user()->name;
    
        $thread = auth()->user()->threads()->create($validated);
    
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
            'content' => 'required',
        ]);

        $thread->comments()->create([
            'user_id' => auth()->id(),
            'content' => $validated['content'],
        ]);

        return back();
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
            'content' => 'required',
        ]);

        $thread->update($validated);

        return redirect()->route('threads.show', $thread);
    }

    public function destroy(Thread $thread)
    {
        $this->authorize('delete', $thread);
        
        $thread->delete();

        return redirect()->route('threads.index');
    }
}