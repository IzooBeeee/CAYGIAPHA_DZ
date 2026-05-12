<?php

namespace App\Policies;

use App\Models\User;
use App\Models\CustomEvent;

class CustomEventPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isActive();
    }

    public function view(User $user, CustomEvent $event): bool
    {
        return $user->isActive();
    }

    public function create(User $user): bool
    {
        return $user->isEditor();
    }

    public function update(User $user, CustomEvent $event): bool
    {
        return $user->isEditor();
    }

    public function delete(User $user, CustomEvent $event): bool
    {
        return $user->isEditor();
    }
}
