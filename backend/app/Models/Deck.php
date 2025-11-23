<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Card;
use App\Models\Decks_cards;

class Deck extends Model
{
    protected $primaryKey = 'deck_id';
    public function cards()
    {
        return $this->belongsToMany(Card::class, Decks_cards::class, 'deck_id', 'card_id');
    }
}
