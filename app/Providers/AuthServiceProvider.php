<?php
namespace App\Providers;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        \App\Models\Thread::class => \App\Policies\ThreadPolicy::class,
        \App\Models\Comment::class => \App\Policies\CommentPolicy::class,
    ];
    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Gate::define('manage-users', function (User $user) {
            return $user->role == 'admin';
        });
        Gate::define('manage-courses', function (User $user) {
            return $user->role == 'alumni';
        });
    }
}