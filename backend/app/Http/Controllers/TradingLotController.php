<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\Trading_lot;

class TradingLotController extends Controller
{
    public function getLot($id)
    {
        $lot = Trading_lot::where('lot_id', '=', $id)->first();
        if (!$lot)
            return response()->json(['message' => 'Лот не найден', 'status' => 404], 404);
        return response()->json(['lot' => $lot, 'status' => 200], 200);
    }
    public function getLots()
    {
        $lots = Trading_lot::paginate(8);
        return response()->json(['lots' => $lots, 'status' => 200], 200);
    }
    public function createLot(Request $request)
    {
        $lot = $request->only('lot_name', 'lot_description', 'price');
        $lot['user_id'] = Auth::id();
        Trading_lot::insert($lot);
        return response()->json(['message' => "Успешно добавлено", 'status' => 200], 200);
    }
    public function updateLot(Request $request, $id)
    {
        $lot = Trading_lot::where('lot_id', '=', $id)->first();
        if (!$lot) {
            return response()->json(['message' => "Лота не существует", 'status' => 404], 404);
        }
        $updated_data = [
            "lot_name" => $request->lot_name ?? $lot->lot_name,
            'lot_description' => $request->lot_description ?? $lot->lot_description,
            'price' => $request->price ?? $lot->price
        ];
        Trading_lot::where('lot_id', '=', $lot->lot_id)->update($updated_data);
        return response()->json(['message' => "Успешно обновлено", 'status' => 200], 200);
    }
    public function deleteLot($id)
    {
        $lot = Trading_lot::where('lot_id', '=', $id)->first();
        if (!$lot) {
            return response()->json(['message' => "Лота не существует", 'status' => 404], 404);
        }
        Trading_lot::where('lot_id', '=', $id)->delete();
        return response()->json(['message' => 'Успешно удалено', 'status' => 200], 200);
    }
}
