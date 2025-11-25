<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Set;
use App\Models\Cards_sets;
use App\Models\Card;
use Illuminate\Support\Facades\Auth;

class SetController extends Controller
{
    public function getSet($id)
    {
        $set = Set::where('set_id', '=', $id)->first();
        return response()->json(['set' => $set, 'status' => 200], 200);
    }
    public function getSets(Request $request)
    {
        $search = $request->query('search');
        if ($search) {
            $sets = Set::where('set_name', 'ilike', '%' . $search . '%')->orderBy('set_name')->paginate(8);
            return response()->json(['sets' => $sets, 'status' => 200], 200);
        }
        $sets = Set::orderBy('set_name')->paginate(8);
        return response()->json(['sets' => $sets, 'status' => 200], 200);
    }
    public function createSet(Request $request)
    {
        if ($request->user()->cannot('create', Set::class)) {
            return response()->json(['message' => "У вас недостаточно прав для этого действия", 'status' => 404], 404);
        }
        $set = $request->only('set_name', 'number_of_cards', 'date_of_release');
        Set::insert($set);
        return response()->json(['message' => "Успешно добавлено", 'status' => 200], 200);
    }
    public function updateSet(Request $request, $id)
    {
        if ($request->user()->cannot('update', Set::class)) {
            return response()->json(['message' => "У вас недостаточно прав для этого действия", 'status' => 404], 404);
        }
        $set = Set::where('set_id', '=', $id)->first();
        if (!$set) {
            return response()->json(['message' => "Сета не существует", 'status' => 404], 404);
        }
        $updated_data = [
            "set_name" => $request->set_name ?? $set->set_name,
            'number_of_cards' => $request->number_of_cards ?? $set->number_of_cards,
            'date_of_release' => $request->date_of_release ?? $set->date_of_release
        ];
        Set::where('set_id', '=', $id)->update($updated_data);
        return response()->json(['message' => "Успешно обновлено", 'status' => 200], 200);
    }
    public function deleteSet($id)
    {
        if (Auth::user()->cannot('delete', Set::class)) {
            return response()->json(['message' => "У вас недостаточно прав для этого действия", 'status' => 404], 404);
        }
        $set = Set::where('set_id', '=', $id)->first();
        if (!$set) {
            return response()->json(['message' => "Сета не существует", 'status' => 404], 404);
        }
        Set::where('set_id', '=', $id)->delete();
        return response()->json(['message' => 'Успешно удалено', 'status' => 200], 200);
    }
    public function addCardToSet(Request $request)
    {
        if ($request->user()->cannot('addCard', Set::class)) {
            return response()->json(['message' => "У вас недостаточно прав для этого действия", 'status' => 404], 404);
        }
        $set_id = Set::where('set_name', '=', $request->set_name)->value('set_id');
        if (!$set_id) {
            return response()->json(['message' => "Сета не существует", 'status' => 404], 404);
        }
        $card_id = Card::where('card_name', '=', $request->card_name)->value('card_id');
        if (!$card_id) {
            return response()->json(['message' => "Карты не существует", 'status' => 404], 404);
        }
        $cards_sets = Cards_sets::where('card_id', '=', $card_id)->where('set_id', '=', $set_id)->first();
        if ($cards_sets) {
            return response()->json(['message' => "Карта уже существует в сете", 'status' => 404], 404);
        }
        Cards_sets::insert(['set_id' => $set_id, 'card_id' => $card_id]);
        return response()->json(['message' => "Успешно добавлено", 'status' => 200], 200);
    }
    public function removeCardFromSet(Request $request)
    {
        if ($request->user()->cannot('removeCard', Set::class)) {
            return response()->json(['message' => "У вас недостаточно прав для этого действия", 'status' => 404], 404);
        }
        $set_id = Set::where('set_name', '=', $request->set_name)->value('set_id');
        if (!$set_id) {
            return response()->json(['message' => "Сета не существует", 'status' => 404], 404);
        }
        $card_id = Card::where('card_name', '=', $request->card_name)->value('card_id');
        if (!$card_id) {
            return response()->json(['message' => "Карты не существует", 'status' => 404], 404);
        }
        $cards_sets = Cards_sets::where('card_id', '=', $card_id)->where('set_id', '=', $set_id)->first();
        if (!$cards_sets) {
            return response()->json(['message' => "Карты в сете не существует", 'status' => 404], 404);
        }
        Cards_sets::where('card_id', '=', $card_id)->where('set_id', '=', $set_id)->delete();
        return response()->json(['message' => "Успешно удалено", 'status' => 200], 200);
    }
}
