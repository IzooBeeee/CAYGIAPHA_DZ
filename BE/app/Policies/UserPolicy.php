<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Person;
use App\Models\Relationship;
use App\Models\CustomEvent;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, User $model): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, User $model): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, User $model): bool
    {
        return $user->isAdmin();
    }

    public function setRole(User $user, User $model): bool
    {
        return $user->isAdmin();
    }

    public function setActive(User $user, User $model): bool
    {
        return $user->isAdmin();
    }
}
