<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Cards_keywords;
use App\Models\Cards_sets;
use App\Models\Cards_subtypes;
use App\Models\Cards_supertypes;
use App\Models\Cards_types;
use App\Models\Format;
use App\Models\Keyword;
use App\Models\Mana_value;
use App\Models\Set;
use App\Models\Subtype;
use App\Models\Supertype;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Restricted_card;

class CardController extends Controller
{
    public function getCard($id)
    {
        $card = Card::where('card_id', '=', $id)->first();
        if (!$card)
            return response()->json(['message' => 'Карта не найдена', 'status' => 404], 404);
        $mana_value = $card->mana_value;
        $types = $card->types;
        $subtypes = $card->subtypes;
        $supertypes = $card->supertypes;
        $keywords = $card->keywords;
        return response()->json(
            [
                'card' => $card,
                'mana_value' => $mana_value,
                'types' => $types,
                'subtypes' => $subtypes,
                'supertypes' => $supertypes,
                'keywords' => $keywords,
                'status' => 200
            ],
            200
        );
    }
    public function getCards(Request $request)
    {
        $search = $request->query('search');
        if ($search) {
            $cards = Card::where('card_name', 'ilike', '%' . $search . '%')->orderBy('card_name')->paginate(6);
        } else {
            $cards = Card::orderBy('card_name')->paginate(6);
        }
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
        return response()->json(['cards' => $returnCards, 'status' => 200], 200);
    }
    public function createCard(Request $request)
    {
        if ($request->user()->cannot('create', Card::class)) {
            return response()->json(['message' => "У вас недостаточно прав для этого действия", 'status' => 404], 404);
        }
        DB::transaction(function () use ($request) {
            Card::insert($request->only('card_name', 'price', 'text_rules', 'illustration_author', 'flavor_text', 'image_href', 'power', 'thoughtness'));
            $card_id = Card::where('card_name', '=', $request->card_name)->value('card_id');
            foreach ($request->keywords ?? [] as $keyword) {
                $keyword_id = Keyword::where('keyword_name', '=', $keyword)->value('keyword_id');
                Cards_keywords::insert(['card_id' => $card_id, 'keyword_id' => $keyword_id]);
            }
            foreach ($request->types as $type) {
                $type_id = Type::where('type_name', '=', $type)->value('type_id');
                Cards_types::insert(['card_id' => $card_id, 'type_id' => $type_id]);
            }
            foreach ($request->subtypes ?? [] as $subtype) {
                $subtype_id = Subtype::where('subtype_name', '=', $subtype)->value('subtype_id');
                Cards_subtypes::insert(['card_id' => $card_id, 'subtype_id' => $subtype_id]);
            }
            foreach ($request->supertypes ?? [] as $supertype) {
                $supertype_id = Supertype::where('supertype_name', '=', $supertype)->value('supertype_id');
                Cards_supertypes::insert(['card_id' => $card_id, 'supertype_id' => $supertype_id]);
            }
            $mana_value = $request->only('white_mana', 'blue_mana', 'black_mana', 'red_mana', 'green_mana', 'colorless_mana');
            Mana_value::where('card_id', '=', $card_id)->update($mana_value);
        }, 3);
        return response()->json(['message' => "Успешно добавлено", 'status' => 200], 200);
    }
    public function updateCard(Request $request, $id)
    {
        if ($request->user()->cannot('update', Card::class)) {
            return response()->json(['message' => "У вас недостаточно прав для этого действия", 'status' => 404], 404);
        }
        $card = Card::where('card_id', '=', $id)->first();
        if (!$card) {
            return response()->json(['message' => "Карты не существует", 'status' => 404], 404);
        }
        DB::transaction(function () use ($request, $id) {
            $card = Card::where('card_id', '=', $id)->first();
            $updated_data_card = [
                'card_name' => $request->card_name ?? $card->card_name,
                'price' => $request->price ?? $card->price,
                'text_rules' => $request->text_rules ?? $card->text_rules,
                'illustration_author' => $request->illustration_author ?? $card->illustration_author,
                'flavor_text' => $request->flavor_text ?? $card->flavor_text,
                'image_href' => $request->image_href ?? $card->image_href,
                'power' => $request->power ?? $card->power,
                'thoughtness' => $request->thoughtness ?? $card->thoughtness,
            ];
            Card::where('card_id', '=', $id)->update($updated_data_card);

            if ($request->keywords) {
                Cards_keywords::where('card_id', '=', $id)->delete();
                foreach ($request->keywords as $keyword) {
                    $keyword_id = Keyword::where('keyword_name', '=', $keyword)->value('keyword_id');
                    Cards_keywords::insert(['card_id' => $id, 'keyword_id' => $keyword_id]);
                }
            }

            if ($request->types) {
                Cards_types::where('card_id', '=', $id)->delete();
                foreach ($request->types as $type) {
                    $type_id = Type::where('type_name', '=', $type)->value('type_id');
                    Cards_types::insert(['card_id' => $id, 'type_id' => $type_id]);
                }
            }

            if ($request->subtypes) {
                Cards_subtypes::where('card_id', '=', $id)->delete();
                foreach ($request->subtypes as $subtype) {
                    $subtype_id = Subtype::where('subtype_name', '=', $subtype)->value('subtype_id');
                    Cards_subtypes::insert(['card_id' => $id, 'subtype_id' => $subtype_id]);
                }
            }

            if ($request->supertypes) {
                Cards_supertypes::where('card_id', '=', $id)->delete();
                foreach ($request->supertypes as $supertype) {
                    $supertype_id = Supertype::where('supertype_name', '=', $supertype)->value('supertype_id');
                    Cards_supertypes::insert(['card_id' => $id, 'supertype_id' => $supertype_id]);
                }
            }

            if (!in_array(null, $request->only('white_mana', 'blue_mana', 'black_mana', 'red_mana', 'green_mana', 'colorless_mana'), true)) {
                $mana_value = $request->only('white_mana', 'blue_mana', 'black_mana', 'red_mana', 'green_mana', 'colorless_mana');
                Mana_value::where('card_id', '=', $id)->update($mana_value);
            }
            if ($request->set_name) {
                Cards_sets::where('card_id', '=', $id)->delete();
                $set_id = Set::where('set_name', '=', $request->set_name)->value("set_id");
                Cards_sets::insert(['card_id' => $id, 'set_id' => $set_id]);
            }
        }, 3);
        return response()->json(['message' => "Успешно обновлено", 'status' => 200], 200);
    }
    public function deleteCard($id)
    {
        if (Auth::user()->cannot('delete', Card::class)) {
            return response()->json(['message' => "У вас недостаточно прав для этого действия", 'status' => 404], 404);
        }
        $card = Card::where('card_id', '=', $id)->first();
        if (!$card) {
            return response()->json(['message' => "Карты не существует", 'status' => 404], 404);
        }
        Card::where('card_id', '=', $id)->delete();
        return response()->json(['message' => "Успешно удалено", 'status' => 200], 200);
    }
    public function getRestrictedCards($format_name = null)
    {
        if ($format_name) {
            $format = Format::where('format_name', '=', $format_name)->first();
            if (!$format) {
                return response()->json(['message' => 'Формата не существует', 'status' => 404], 404);
            }
            $cards = Restricted_card::where('format_name', '=', $format_name)->join('cards', 'restricted_cards.card_id', '=', 'cards.card_id')->orderBy('card_name')->paginate(9);
            return response()->json(['cards' => $cards, 'status' => 200], 200);
        }
        $cards = Restricted_card::join('cards', 'restricted_cards.card_id', '=', 'cards.card_id')->orderBy('card_name')->paginate(9);
        return response()->json(['cards' => $cards, 'status' => 200], 200);
    }
    public function createRestrictedCard(Request $request)
    {
        if ($request->user()->cannot('createRestrictedCard', Card::class)) {
            return response()->json(['message' => "У вас недостаточно прав для этого действия", 'status' => 404], 404);
        }
        $card_id = Card::where('card_name', '=', $request->card_name)->value('card_id');
        $restriction = $request->only('format_name', 'restriction_type', 'restriction_description', 'date_of_restriction');
        $restriction['card_id'] = $card_id;
        Restricted_card::insert($restriction);
        return response()->json(['message' => "Успешно добавлено", 'status' => 200], 200);
    }
    public function updateRestrictedCard(Request $request, $id)
    {
        if ($request->user()->cannot('updateRestrictedCard', Card::class)) {
            return response()->json(['message' => "У вас недостаточно прав для этого действия", 'status' => 404], 404);
        }
        $card = Restricted_card::where('card_id', '=', $id)->where('format_name', '=', $request->format_name)->first();
        if (!$card) {
            return response()->json(['message' => "Ограничения не существует", 'status' => 404], 404);
        }
        $updated_data = [
            'restriction_type' => $request->restriction_type ?? $card->restriction_type,
            'restriction_description' => $request->restriction_description ?? $card->restriction_description,
            'date_of_restriction' => $request->date_of_restriction ?? $card->date_of_restriction
        ];
        Restricted_card::where('card_id', '=', $id)->where('format_name', '=', $request->format_name)->update($updated_data);
        return response()->json(['message' => "Успешно обновлено", 'status' => 200], 200);
    }
    public function deleteRestrictedCard(Request $request, $id)
    {
        if (Auth::user()->cannot('deleteRestrictedCard', Card::class)) {
            return response()->json(['message' => "У вас недостаточно прав для этого действия", 'status' => 404], 404);
        }
        $card = Restricted_card::where('card_id', '=', $id)->where('format_name', '=', $request->format_name)->first();
        if (!$card) {
            return response()->json(['message' => "Ограничения не существует", 'status' => 404], 404);
        }
        Restricted_card::where('card_id', '=', $id)->where('format_name', '=', $request->format_name)->delete();
        return response()->json(['message' => 'Успешно удалено', 'status' => 200], 200);
    }
    public function getKeywords()
    {
        $keywords = Keyword::all();
        return response()->json(['keywords' => $keywords, 'status' => 200], 200);
    }
}
