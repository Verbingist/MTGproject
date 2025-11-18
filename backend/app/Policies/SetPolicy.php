<?php

namespace App\Policies;

use App\Models\User;

class SetPolicy
{
    public function create(User $user): bool
    {
        if ($user->role == 1)
            return true;
        return false;
    }
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
    public function addCard(User $user)
    {
        if ($user->role == 1 || $user->role == 2)
            return true;
        return false;
    }
    public function removeCard(User $user)
    {
        if ($user->role == 1 || $user->role == 2)
            return true;
        return false;
    }
}
