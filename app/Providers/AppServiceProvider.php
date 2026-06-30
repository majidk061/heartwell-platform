<?php

namespace App\Providers;

use App\Domains\Content\Actions\GetSiteSettingsAction;
use App\Domains\Integrations\Contracts\AcuityServiceInterface;
use App\Domains\Integrations\Contracts\MailchimpServiceInterface;
use App\Domains\Integrations\Contracts\SendGridServiceInterface;
use App\Domains\Integrations\Services\AcuityService;
use App\Domains\Integrations\Services\MailchimpService;
use App\Domains\Integrations\Services\SendGridService;
use App\Domains\Integrations\Services\SettingsResolver;
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
        try {
            app(SettingsResolver::class)->mergeIntoConfig();
        } catch (\Throwable) {
            // Settings table may not exist during initial migrate
        }

        try {
            view()->share('siteSettings', app(GetSiteSettingsAction::class)->execute());
        } catch (\Throwable) {
            // Database may be unavailable during isolated unit tests
        }
    }
}
