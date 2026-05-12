<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Relationship;

class RelationshipPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isActive();
    }

    public function view(User $user, Relationship $relationship): bool
    {
        return $user->isActive();
    }

    public function create(User $user): bool
    {
        return $user->isEditor();
    }

    public function update(User $user, Relationship $relationship): bool
    {
        return $user->isEditor();
    }

    public function delete(User $user, Relationship $relationship): bool
    {
        return $user->isEditor();
    }
}
