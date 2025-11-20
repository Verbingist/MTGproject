<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Format;

class FormatController extends Controller
{
    public function getFormat($format_name)
    {
        $format = Format::where('format_name', '=', $format_name)->first();
        if (!$format) {
            return response()->json(['message' => "Формата не существует", 'status' => 404], 404);
        }
        return response()->json(['format' => $format, 'status' => 200], 200);
    }
    public function getFormats()
    {
        $formats = Format::paginate(8);
        return response()->json(['formats' => $formats, 'status' => 200], 200);
    }
    public function createFormat(Request $request)
    {
        if ($request->user()->cannot('create', Format::class)) {
            return response()->json(['message' => "У вас недостаточно прав для этого действия", 'status' => 404], 404);
        }
        $format = $request->only('format_name', 'min_cards_in_deck', 'max_cards_in_deck', 'format_description');
        Format::insert($format);
        return response()->json(['message' => "Успешно добавлено", 'status' => 200], 200);
    }
    public function updateFormat(Request $request, $format_name)
    {
        if ($request->user()->cannot('update', Format::class)) {
            return response()->json(['message' => "У вас недостаточно прав для этого действия", 'status' => 404], 404);
        }
        $format = Format::where('format_name', '=', $format_name)->first();
        if (!$format) {
            return response()->json(['message' => "Формата не существует", 'status' => 404], 404);
        }
        $updated_data = [
            'format_name' => $request->format_name ?? $format->format_name,
            'min_cards_in_deck' => $request->min_cards_in_deck ?? $format->min_cards_in_deck,
            'max_cards_in_deck' => $request->max_cards_in_deck ?? $format->max_cards_in_deck,
            'format_description' => $request->format_description ?? $format->format_description
        ];
        Format::where('format_name', '=', $format_name)->update($updated_data);
        return response()->json(['message' => "Успешно обновлено", 'status' => 200], 200);
    }
    public function deleteFormat(Request $request, $format_name)
    {
        if ($request->user()->cannot('delete', Format::class)) {
            return response()->json(['message' => "У вас недостаточно прав для этого действия", 'status' => 404], 404);
        }
        $format = Format::where('format_name', '=', $format_name)->first();
        if (!$format) {
            return response()->json(['message' => "Формата не существует", 'status' => 404], 404);
        }
        Format::where('format_name', '=', $format_name)->delete();
        return response()->json(['message' => 'Успешно удалено', 'status' => 200], 200);
    }
}
