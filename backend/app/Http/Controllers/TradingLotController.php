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
    public function getLots(Request $request)
    {
        $search = $request->query('search');
        if ($search) {
            $lots = Trading_lot::where('lot_name', 'ilike', '%' . $search . '%')->orderBy('lot_name')->paginate(9);
            return response()->json(['lots' => $lots, 'status' => 200], 200);
        }
        $lots = Trading_lot::orderBy('lot_name')->paginate(9);
        return response()->json(['lots' => $lots, 'status' => 200], 200);
    }
    public function getUserLots(Request $request, $id = null)
    {
        if (!$id) {
            if (Auth::check()) {
                $id = Auth::id();
            } else {
                return response()->json(['message' => 'Не аудентифицированы', 'status' => 401], 401);
            }
        }
        $search = $request->query('search');
        if ($search) {
            $lots = Trading_lot::where('lot_name', 'ilike', '%' . $search . '%')->where('user_id', '=', $id)->orderBy('lot_name')->paginate(9);
            return response()->json(['lots' => $lots, 'status' => 200], 200);
        }
        $lots = Trading_lot::where('user_id', '=', $id)->orderBy('lot_name')->paginate(9);
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
