<?php

namespace App\Policies;

use App\Models\Format;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FormatPolicy
{
    public function create(User $user): bool
    {
        if ($user->role == 1 || $user->role == 2)
            return true;
        return false;
    }
    public function update(User $user): bool
    {
        if ($user->role == 1 || $user->role == 2)
            return true;
        return false;
    }
    public function delete(User $user): bool
    {
        if ($user->role == 1 || $user->role == 2)
            return true;
        return false;
    }
}
