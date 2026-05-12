<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Person;

class DataPolicy
{
    public function export(User $user, Person $person): bool
    {
        return $user->isAdmin();
    }

    public function import(User $user): bool
    {
        return $user->isAdmin();
    }
}
