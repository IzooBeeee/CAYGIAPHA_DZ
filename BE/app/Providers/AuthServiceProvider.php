<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\User;
use App\Models\Person;
use App\Models\Relationship;
use App\Models\CustomEvent;
use App\Policies\UserPolicy;
use App\Policies\PersonPolicy;
use App\Policies\RelationshipPolicy;
use App\Policies\CustomEventPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
        Person::class => PersonPolicy::class,
        Relationship::class => RelationshipPolicy::class,
        CustomEvent::class => CustomEventPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
