<?php

namespace App\Providers;

use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;

class CarbonServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Carbon::macro('date_api', function () {
            return $this;
        });

        Carbon::macro('date_time_export', function () {
            return Carbon::parse($this)->format(config('dates.api_format_date_time_outcome'));
        });

        Carbon::macro('date_export', function () {
            return Carbon::parse($this)->format(config('dates.api_format_date_time_outcome'));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
