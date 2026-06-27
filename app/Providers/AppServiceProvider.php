<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Gate;
use App\Models\ExamResult;
use App\Models\ExamSession;
use App\Models\Center;
use App\Policies\ExamResultPolicy;
use App\Policies\ExamSessionPolicy;


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
        Schema::defaultStringLength(191);

        Gate::policy(ExamResult::class, ExamResultPolicy::class);
        Gate::policy(ExamSession::class, ExamSessionPolicy::class);

        RateLimiter::for('public-results', function (Request $request) {

            return [
                Limit::perMinute(10)
                    ->by(
                        $request->ip() . '|' .
                        $request->input('center_slug')
                    )
            ];
        });
    }
}
