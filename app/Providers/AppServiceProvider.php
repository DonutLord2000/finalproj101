<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Blade;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Storage::macro('url', function ($path) {
            $path = ltrim($path, '/');
            
            return config('filesystems.disks.s3.url') . '/' . $path;
        });

        // Add a directive to include the Lumnix chatbot
        Blade::directive('lumnixChatbot', function () {
            return '<?php echo "<script src=\"" . asset(\'js/lumnix-chatbot.js\') . "\"></script>
                          <link href=\"" . asset(\'css/lumnix-chatbot.css\') . "\" rel=\"stylesheet\">"; ?>';
        });
    }
}
