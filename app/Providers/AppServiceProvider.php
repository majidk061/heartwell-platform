<?php

namespace App\Providers;

use App\Domains\Integrations\Contracts\AcuityServiceInterface;
use App\Domains\Integrations\Contracts\MailchimpServiceInterface;
use App\Domains\Integrations\Contracts\SendGridServiceInterface;
use App\Domains\Integrations\Services\AcuityService;
use App\Domains\Integrations\Services\MailchimpService;
use App\Domains\Integrations\Services\SendGridService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(MailchimpServiceInterface::class, MailchimpService::class);
        $this->app->bind(SendGridServiceInterface::class, SendGridService::class);
        $this->app->bind(AcuityServiceInterface::class, AcuityService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
