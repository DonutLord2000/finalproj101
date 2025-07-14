<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Thread;
use Illuminate\Http\Request;
use App\Services\ContentModerationService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth; // Import Auth facade

class CommentController extends Controller
{
    protected $moderationService;

    public function __construct(ContentModerationService $moderationService)
    {
        $this->moderationService = $moderationService;
    }

    public function edit(Comment $comment)
    {
        // Only allow editing if not anonymous or if user is admin
        if ($comment->is_anonymous && Auth::check() && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }
        // Check if user can edit this comment
        $this->authorize('update', $comment);

        return view('comments.edit', compact('comment'));
    }

    public function update(Request $request, Comment $comment)
    {
        // Only allow updating if not anonymous or if user is admin
        if ($comment->is_anonymous && Auth::check() && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }
        // Check if user can edit this comment
        $this->authorize('update', $comment);

        $validated = $request->validate([
            'content' => 'required|max:10000',
        ]);

        // Count words and validate maximum words
        $wordCount = str_word_count($validated['content']);

        // Count words and validate maximum words
        if ($wordCount > 700) {
            return response()->json([
                'success' => false,
                'message' => 'Comment exceeds the maximum limit of 700 words.'
            ], 422);
        }

        // Check content with OpenAI moderation
        Log::info('Checking comment update content with moderation service');
        $moderationResult = $this->moderationService->moderateContent($validated['content']);

        if (!$moderationResult['safe']) {
            Log::warning('Comment update content failed moderation check', ['reason' => $moderationResult['message']]);
            return response()->json([
                'success' => false,
                'message' => 'Your comment contains inappropriate content: ' . $moderationResult['message']
            ], 422);
        }

        // Store original content before updating (this line was already there, keep it)
        // $originalContent = $comment->content;

        // Update the comment with edited flag
        $comment->update([
            'content' => $validated['content'],
            'edited_at' => now(),
            'edited_by' => Auth::id(), // Use Auth::id()
        ]);

        Log::info('Comment updated successfully', ['comment_id' => $comment->id]);

        // Check if request wants JSON
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Comment updated successfully',
                'redirect' => route('threads.show', $comment->thread_id)
            ]);
        }

        // Regular form submission fallback
        return redirect()->route('threads.show', $comment->thread_id);
    }

    public function destroy(Comment $comment)
    {
        // Only allow deleting if not anonymous or if user is admin
        if ($comment->is_anonymous && Auth::check() && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }
        // Check if user can delete this comment
        $this->authorize('delete', $comment);

        // Soft delete the comment with deletion info
        $comment->update([
            'deleted_at' => now(),
            'deleted_by' => Auth::id(), // Use Auth::id()
        ]);

        return redirect()->route('threads.show', $comment->thread_id);
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
