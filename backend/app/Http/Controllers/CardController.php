<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Cards_keywords;
use App\Models\Cards_sets;
use App\Models\Cards_subtypes;
use App\Models\Cards_supertypes;
use App\Models\Cards_types;
use App\Models\Keyword;
use App\Models\Mana_value;
use App\Models\Set;
use App\Models\Subtype;
use App\Models\Supertype;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CardController extends Controller
{
    public function getCard($id)
    {
        $card = Card::where('card_id', '=', $id)->first();
        if (!$card)
            return response()->json(['message' => 'Карта не найдена', 'status' => 404], 404);
        return response()->json(['card' => $card, 'status' => 200], 200);
    }
    public function getCards()
    {
        $cards = Card::orderBy('card_name')->paginate(8);
        return response()->json(['cards' => $cards, 'status' => 200], 200);
    }
    public function createCard(Request $request)
    {
        if ($request->user()->cannot('create', Card::class)) {
            return response()->json(['message' => "У вас недостаточно прав для этого действия", 'status' => 404], 404);
        }
        Card::insert($request->only('card_name', 'price', 'text_rules', 'illustration_author', 'flavor_text', 'image_href', 'power', 'thoughtness'));
        $card_id = Card::where('card_name', '=', $request->card_name)->value('card_id');
        foreach ($request->keywords as $keyword) {
            $keyword_id = Keyword::where('keyword_name', '=', $keyword)->value('keyword_id');
            Cards_keywords::insert(['card_id' => $card_id, 'keyword_id' => $keyword_id]);
        }
        foreach ($request->types as $type) {
            $type_id = Type::where('type_name', '=', $type)->value('type_id');
            Cards_types::insert(['card_id' => $card_id, 'type_id' => $type_id]);
        }
        foreach ($request->subtypes as $subtype) {
            $subtype_id = Subtype::where('subtype_name', '=', $subtype)->value('subtype_id');
            Cards_subtypes::insert(['card_id' => $card_id, 'subtype_id' => $subtype_id]);
        }
        foreach ($request->supertypes as $supertype) {
            $supertype_id = Supertype::where('supertype_name', '=', $supertype)->value('supertype_id');
            Cards_supertypes::insert(['card_id' => $card_id, 'supertype_id' => $supertype_id]);
        }
        $mana_value = $request->only('white_mana', 'blue_mana', 'black_mana', 'red_mana', 'green_mana', 'colorless_mana');
        Mana_value::where('card_id', '=', $card_id)->update($mana_value);
        $set_id = Set::where('set_name', '=', $request->set_name)->value("set_id");
        Cards_sets::insert(['card_id' => $card_id, 'set_id' => $set_id]);
        return response()->json(['message' => "Успешно добавлено", 'status' => 200], 200);
    }
    public function updateCard(Request $request, $id)
    {

    }
    public function deleteCards()
    {

    }
    public function getRestrictedCards()
    {

    }
    public function createRestrictedCard()
    {

    }
    public function updateRestrictedCard()
    {

    }
    public function deleteRestrictedCard()
    {

    }
}
