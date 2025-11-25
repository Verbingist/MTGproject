<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Deck;
use App\Models\Card;
use App\Models\Decks_cards;
use Illuminate\Support\Facades\Auth;

class DeckController extends Controller
{
    public function getDeck($id)
    {
        $deck = Deck::where('deck_id', '=', $id)->first();
        if (!$deck)
            return response()->json(['message' => 'Колода не найдена', 'status' => 404], 404);
        return response()->json(['deck' => $deck, 'status' => 200], 200);
    }
    public function getDecks(Request $request, $id = null)
    {
        if ($id) {
            $search = $request->query('search');
            if ($search) {
                $decks = Deck::where('deck_name', 'ilike', '%' . $search . '%')
                    ->where('user_id', '=', $id)->orderBy('deck_name')->paginate(9);
                return response()->json(['decks' => $decks, 'status' => 200], 200);
            }
            $decks = Deck::where('user_id', '=', $id)->orderBy('deck_name')->paginate(9);
            return response()->json(['decks' => $decks, 'status' => 200], 200);
        }
        $search = $request->query('search');
        if ($search) {
            $decks = Deck::where('deck_name', 'like', '%' . $search . '%')->orderBy('deck_name')->paginate(9);
            return response()->json(['decks' => $decks, 'status' => 200], 200);
        }
        $decks = Deck::orderBy('deck_name')->paginate(9);
        return response()->json(['decks' => $decks, 'status' => 200], 200);
    }
    public function getMyDecks(Request $request)
    {
        $search = $request->query('search');
        if ($search) {
            $decks = Deck::where('deck_name', 'ilike', '%' . $search . '%')->where('user_id', '=', Auth::id())->orderBy('deck_name')->paginate(9);
            return response()->json(['decks' => $decks, 'status' => 200], 200);
        }
        $decks = Deck::where('user_id', '=', Auth::id())->orderBy('deck_name')->paginate(9);
        return response()->json(['decks' => $decks, 'status' => 200], 200);
    }
    public function getCards($deck_id)
    {
        $deck = Deck::where('deck_id', '=', $deck_id)->first();
        $cards = $deck->cards;
        $commander = Card::where('card_id', '=', $deck->commander_card_id)->first();
        $returnCards = [];
        foreach ($cards as $card) {
            $mana_value = $card->mana_value;
            $types = $card->types;
            $subtypes = $card->subtypes;
            $supertypes = $card->supertypes;
            $keywords = $card->keywords;
            $returnCard = [
                'card' => $card,
                'mana_value' => $mana_value,
                'types' => $types,
                'subtypes' => $subtypes,
                'supertypes' => $supertypes,
                'keywords' => $keywords
            ];
            $returnCards[] = $returnCard;
        }
        return response()->json(['cards' => $returnCards, 'commander' => $commander, 'status' => 200], status: 200);
    }
    public function createDeck(Request $request)
    {
        if ($request->commander_card_name) {
            $commander_id = Card::where('card_name', '=', $request->commander_card_name)->first();
            if (!$commander_id) {
                return response()->json(['message' => "Командира не существует", 'status' => 404], 404);
            }
            $request['commander_card_id'] = $commander_id->card_id;
        }
        $deck = $request->only('deck_name', 'format_name', 'power_level', 'commander_card_id');
        $deck['user_id'] = Auth::id();
        $id = Deck::insertGetId($deck, 'deck_id');
        return response()->json(['message' => "Успешно добавлено", 'deck_id' => $id, 'status' => 200], 200);
    }
    public function updateDeck(Request $request, $id)
    {
        $deck = Deck::where('deck_id', '=', $id)->first();
        if (!$deck) {
            return response()->json(['message' => "Колоды не существует", 'status' => 404], 404);
        }
        if ($request->user()->cannot('update', $deck)) {
            return response()->json(['message' => "У вас недостаточно прав для этого действия", 'status' => 404], 404);
        }
        $updated_data = [
            "deck_name" => $request->deck_name ?? $deck->deck_name,
            "format_name" => $request->format_name ?? $deck->format_name,
            "power_level" => $request->power_level ?? $deck->power_level,
            "commander_card_id" => $request->commander_card_id ?? $deck->commander_card_id,
        ];
        Deck::where('deck_id', '=', $id)->update($updated_data);
        return response()->json(['message' => "Успешно обновлено", 'status' => 200], 200);
    }
    public function deleteDecks($id)
    {
        $deck = Deck::where('deck_id', '=', $id)->first();
        if (auth()->user()->cannot('delete', $deck)) {
            return response()->json(['message' => "У вас недостаточно прав для этого действия", 'status' => 404], 404);
        }
        if (!$deck) {
            return response()->json(['message' => "Колоды не существует", 'status' => 404], 404);
        }
        Deck::where('deck_id', '=', $id)->delete();
        return response()->json(['message' => 'Успешно удалено', 'status' => 200], 200);
    } 
    public function setCommander(Request $request, $id)
    {
        $deck = Deck::where('deck_id', '=', $id)->first();
        if (!$deck) {
            return response()->json(['message' => "Колоды не существует", 'status' => 404], 404);
        }
        if ($request->user()->cannot('addCard', $deck)) {
            return response()->json(['message' => "У вас недостаточно прав для этого действия", 'status' => 404], 404);
        }
        $card_id = Card::where('card_name', '=', $request->card_name)->value('card_id');
        if (!$card_id) {
            return response()->json(['message' => "Карты не существует", 'status' => 404], 404);
        }
        $updated_data = [
            "commander_card_id" => $card_id,
        ];
        Deck::where('deck_id', '=', $id)->update($updated_data);
        return response()->json(['message' => 'Успешно добавлено', 'status' => 200], 200);
    }
    public function deleteCommander($id)
    {
        $deck = Deck::where('deck_id', '=', $id)->first();
        if (auth()->user()->cannot('removeCard', $deck)) {
            return response()->json(['message' => "У вас недостаточно прав для этого действия", 'status' => 404], 404);
        }
        if (!$deck) {
            return response()->json(['message' => "Колоды не существует", 'status' => 404], 404);
        }
        $updated_data = [
            "commander_card_id" => null,
        ];
        Deck::where('deck_id', '=', $id)->update($updated_data);
        return response()->json(['message' => 'Успешно удалено', 'status' => 200], 200);
    }
    public function addCardToDeck(Request $request, $id)
    {
        $deck = Deck::where('deck_id', '=', $id)->first();
        if (!$deck) {
            return response()->json(['message' => "Колоды не существует", 'status' => 404], 404);
        }
        if ($request->user()->cannot('addCard', $deck)) {
            return response()->json(['message' => "У вас недостаточно прав для этого действия", 'status' => 404], 404);
        }
        $card_id = Card::where('card_name', '=', $request->card_name)->value('card_id');
        if (!$card_id) {
            return response()->json(['message' => "Карты не существует", 'status' => 404], 404);
        }
        Decks_cards::insert([
            'card_id' => $card_id,
            'deck_id' => $id
        ]);
        return response()->json(['message' => 'Успешно добавлено', 'status' => 200], 200);
    }
    public function removeCardFromDeck(Request $request, $id)
    {
        $deck = Deck::where('deck_id', '=', $id)->first();
        if (!$deck) {
            return response()->json(['message' => "Колоды не существует", 'status' => 404], 404);
        }
        if ($request->user()->cannot('removeCard', $deck)) {
            return response()->json(['message' => "У вас недостаточно прав для этого действия", 'status' => 404], 404);
        }
        $card_id = Card::where('card_name', '=', $request->card_name)->value('card_id');
        if (!$card_id) {
            return response()->json(['message' => "Карты не существует", 'status' => 404], 404);
        }

        $cardExistInDeck = Decks_cards::where('deck_id', '=', $id)
            ->where('card_id', '=', $card_id)->first();
        if (!$cardExistInDeck) {
            return response()->json(['message' => "Карты не существует в колоде", 'status' => 400], 400);
        }

        $decks_card = Decks_cards::where('deck_id', '=', $id)
            ->where('card_id', '=', $card_id)->first();
        if ($decks_card) {
            $decks_card->delete();
        }
        return response()->json(['message' => 'Успешно удалено', 'status' => 200], 200);
    }
    public function isOwnDeck()
    {
        return response()->json(['message' => 'Колода пользователя', 'isOwnDeck' => true, 'status' => 200], 200);
    }
}