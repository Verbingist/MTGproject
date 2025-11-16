<?php

namespace App\Policies;

use App\Models\Deck;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DeckPolicy
{
    public function update(User $user, Deck $deck): bool
    {
        if ($user->id == $deck->user_id || $user->role == 1)
            return true;
        return false;
    }
    public function delete(User $user, Deck $deck): bool
    {
        if ($user->id == $deck->user_id || $user->role == 1)
            return true;
        return false;
    }
    public function addCard(User $user, Deck $deck): bool
    {
        if ($user->id == $deck->user_id || $user->role == 1)
            return true;
        return false;
    }
    public function removeCard(User $user, Deck $deck): bool
    {
        if ($user->id == $deck->user_id || $user->role == 1)
            return true;
        return false;
    }
}
