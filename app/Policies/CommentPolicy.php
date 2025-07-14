<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool // Allow null for guest users
    {
        return true; // All users (guests or authenticated) can view comments
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Comment $comment): bool // Allow null for guest users
    {
        return true; // All users (guests or authenticated) can view a specific comment
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(?User $user): bool // Allow null for guest users
    {
        // Guests can create anonymous comments, authenticated users can create any type
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(?User $user, Comment $comment): bool // Allow null for guest users
    {
        // Only authenticated users can update.
        // A user can update their own non-anonymous comment, or an admin can update any comment (including anonymous)
        if (!$user) {
            return false; // Guests cannot update
        }
        return ($user->id === $comment->user_id && !$comment->is_anonymous) || $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(?User $user, Comment $comment): bool // Allow null for guest users
    {
        // Only authenticated users can delete.
        // A user can delete their own non-anonymous comment, or an admin can delete any comment (including anonymous)
        if (!$user) {
            return false; // Guests cannot delete
        }
        return ($user->id === $comment->user_id && !$comment->is_anonymous) || $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(?User $user, Comment $comment): bool // Allow null for guest users
    {
        // Only admins can restore comments
        return $user && $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(?User $user, Comment $comment): bool // Allow null for guest users
    {
        // Only admins can force delete comments
        return $user && $user->role === 'admin';
    }
}
