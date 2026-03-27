<?php

namespace App\Providers;

use App\Models\Struk;
use App\Observers\StrukObserver;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Struk::observe(StrukObserver::class);

        if (str_starts_with(config('app.url'), 'https://')) {
            URL::forceScheme('https');
            URL::forceRootUrl(config('app.url'));
        }
    }
}
