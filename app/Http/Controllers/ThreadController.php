<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use App\Models\Comment;
use App\Models\Reaction; 
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    public function index()
    {
        $threads = Thread::withCount(['comments', 'upvotes', 'hearts'])
            ->with('user')
            ->latest()
            ->paginate(15);

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