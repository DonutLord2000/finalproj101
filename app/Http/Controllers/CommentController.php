<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Thread;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function edit(Comment $comment)
    {
        // Check if user can edit this comment
        $this->authorize('update', $comment);
        
        return view('comments.edit', compact('comment'));
    }

    public function update(Request $request, Comment $comment)
    {
        // Check if user can edit this comment
        $this->authorize('update', $comment);
        
        $validated = $request->validate([
            'content' => 'required',
        ]);

        // Store original content before updating
        $originalContent = $comment->content;
        
        // Update the comment with edited flag
        $comment->update([
            'content' => $validated['content'],
            'edited_at' => now(),
            'edited_by' => auth()->id(),
        ]);

        return redirect()->route('threads.show', $comment->thread_id);
    }

    public function destroy(Comment $comment)
    {
        // Check if user can delete this comment
        $this->authorize('delete', $comment);
        
        // Soft delete the comment with deletion info
        $comment->update([
            'deleted_at' => now(),
            'deleted_by' => auth()->id(),
        ]);

        return redirect()->route('threads.show', $comment->thread_id);
    }
}