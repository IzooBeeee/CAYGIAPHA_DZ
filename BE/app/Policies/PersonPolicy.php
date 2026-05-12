<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Person;

class PersonPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isActive();
    }

    public function view(User $user, Person $person): bool
    {
        return $user->isActive();
    }

    public function create(User $user): bool
    {
        return $user->isEditor();
    }

    public function update(User $user, Person $person): bool
    {
        return $user->isEditor();
    }

    public function delete(User $user, Person $person): bool
    {
        return $user->isEditor();
    }

    public function updatePrivateDetails(User $user, Person $person): bool
    {
        return $user->isAdmin();
    }
}
