<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Card;

class Decks_cards extends Model
{
    public function cards() {
        return $this->hasMany(Card::class, 'card_id', 'deck_id');
    }
}
