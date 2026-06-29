<?php

namespace App\Providers;

use App\Domains\CRM\Models\Lead;
use App\Models\User;
use App\Policies\LeadPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Lead::class => LeadPolicy::class,
        User::class => UserPolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}
