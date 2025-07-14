<?php

namespace App\Policies;

use App\Models\Thread;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ThreadPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool // Allow null for guest users
    {
        return true; // All users (guests or authenticated) can view threads
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Thread $thread): bool // Allow null for guest users
    {
        return true; // All users (guests or authenticated) can view a specific thread
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(?User $user): bool // Allow null for guest users
    {
        // Guests can create anonymous threads, authenticated users can create any type
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(?User $user, Thread $thread): bool // Allow null for guest users
    {
        // Only authenticated users can update.
        // A user can update their own non-anonymous thread, or an admin can update any thread (including anonymous)
        if (!$user) {
            return false; // Guests cannot update
        }
        return ($user->id === $thread->user_id && !$thread->is_anonymous) || $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(?User $user, Thread $thread): bool // Allow null for guest users
    {
        // Only authenticated users can delete.
        // A user can delete their own non-anonymous thread, or an admin can delete any thread (including anonymous)
        if (!$user) {
            return false; // Guests cannot delete
        }
        return ($user->id === $thread->user_id && !$thread->is_anonymous) || $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(?User $user, Thread $thread): bool // Allow null for guest users
    {
        // Only admins can restore threads
        return $user && $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(?User $user, Thread $thread): bool // Allow null for guest users
    {
        // Only admins can force delete threads
        return $user && $user->role === 'admin';
    }
}
