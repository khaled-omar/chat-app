<?php

namespace App\Providers;

use App\Services\Contracts\OTP;
use App\Services\Mocked\OTPMockedService;
use App\Services\OTPService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Channels\DatabaseChannel;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local', 'testing')) {
            $this->app->bind(OTP::class, OTPMockedService::class);
        } else {
            $this->app->bind(OTP::class, OTPService::class);
        }

        // bind the custom database notification channel
        $this->app->instance(DatabaseChannel::class, new \App\NotificationChannels\DatabaseChannel);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::preventLazyLoading(! app()->isProduction());

        $this->bypassCaptchValidationInLocalEnv();
    }

    /**
     * overwrite google recaptcha validation in local env to be accepted
     *
     * @return void
     */
    protected function bypassCaptchValidationInLocalEnv()
    {
        $this->app['validator']->extend('custom_captcha', function ($attribute, $value) {
            if (app()->environment('local', 'testing')) {
                return true;
            }

            return $this->app['captcha']->verifyResponse($value, $this->app['request']->getClientIp());
        });
    }
}
