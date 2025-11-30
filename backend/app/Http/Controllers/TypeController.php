<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Type;
use Illuminate\Support\Facades\Auth;

class TypeController extends Controller
{
    public function getType($id)
    {
        $type = Type::where('type_id', '=', $id)->first();
        if (!$type)
            return response()->json(['message' => 'Тип не найден', 'status' => 404], 404);
        return response()->json(['types' => $type, 'status' => 200], 200);
    }
    public function getTypes()
    {
        $types = Type::all();
        return response()->json(['types' => $types, 'status' => 200], 200);
    }
    public function createType(Request $request)
    {
        if ($request->user()->cannot('create', Type::class)) {
            return response()->json(['message' => "У вас недостаточно прав для этого действия", 'status' => 404], 404);
        }
        $type = $request->only('type_name');
        Type::insert($type);
        return response()->json(['message' => "Успешно добавлено", 'status' => 200], 200);
    }

    public function updateType(Request $request, $id)
    {
        if ($request->user()->cannot('update', Type::class)) {
            return response()->json(['message' => "У вас недостаточно прав для этого действия", 'status' => 404], 404);
        }
        $type = Type::where('type_id', '=', $id)->first();
        if (!$type) {
            return response()->json(['message' => "Типа не существует", 'status' => 404], 404);
        }
        $updated_data = [
            "type_name" => $request->type_name ?? $type->type_name,
        ];
        Type::where('type_name', '=', $type->type_name)->update($updated_data);
        return response()->json(['message' => "Успешно обновлено", 'status' => 200], 200);
    }
    public function deleteType($id)
    {
        if (Auth::user()->cannot('delete', Type::class)) {
            return response()->json(['message' => "У вас недостаточно прав для этого действия", 'status' => 404], 404);
        }
        $type = Type::where('type_id', '=', $id)->first();
        if (!$type) {
            return response()->json(['message' => "Типа не существует", 'status' => 404], 404);
        }
        Type::where('type_id', '=', $id)->delete();
        return response()->json(['message' => 'Успешно удалено', 'status' => 200], 200);
    }
}
