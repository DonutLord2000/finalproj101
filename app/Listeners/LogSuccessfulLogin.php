<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        // Update the last_login_at timestamp for the user
        DB::table('users')
            ->where('id', $event->user->id)
            ->update(['last_login_at' => Carbon::now()]);
    }
}
