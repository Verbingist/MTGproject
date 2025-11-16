<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function update(User $user): bool
    {
        if ($user->role == 1)
            return true;
        return false;
    }

    public function delete(User $user): bool
    {
        if ($user->role == 1)
            return true;
        return false;
    }
}
