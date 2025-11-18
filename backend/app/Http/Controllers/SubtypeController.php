<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subtype;
use Illuminate\Support\Facades\Auth;

class SubtypeController extends Controller
{
    public function getSubtype($id)
    {
        $subtype = Subtype::where('subtype_id', '=', $id)->first();
        if (!$subtype)
            return response()->json(['message' => 'Подтип не найден', 'status' => 404], 404);
        return response()->json(['subtype' => $subtype, 'status' => 200], 200);
    }
    public function getSubtypes()
    {
        $subtypes = Subtype::paginate(8);
        return response()->json(['subtypes' => $subtypes, 'status' => 200], 200);
    }
    public function createSubtype(Request $request)
    {
        if ($request->user()->cannot('create', Subtype::class)) {
            return response()->json(['message' => "У вас недостаточно прав для этого действия", 'status' => 404], 404);
        }
        $subtype = $request->only('subtype_name');
        Subtype::insert($subtype);
        return response()->json(['message' => "Успешно добавлено", 'status' => 200], 200);
    }
    public function updateSubtype(Request $request, $id)
    {
        if ($request->user()->cannot('update', Subtype::class)) {
            return response()->json(['message' => "У вас недостаточно прав для этого действия", 'status' => 404], 404);
        }
        $subtype = Subtype::where('subtype_id', '=', $id)->first();
        if (!$subtype) {
            return response()->json(['message' => "Подтипа не существует", 'status' => 404], 404);
        }
        $updated_data = [
            "subtype_name" => $request->subtype_name ?? $subtype->subtype_name,
        ];
        Subtype::where('subtype_id', '=', $id)->update($updated_data);
        return response()->json(['message' => "Успешно обновлено", 'status' => 200], 200);
    }
    public function deleteSubtype($id)
    {
        if (Auth::user()->cannot('delete', Subtype::class)) {
            return response()->json(['message' => "У вас недостаточно прав для этого действия", 'status' => 404], 404);
        }
        $subtype = Subtype::where('subtype_id', '=', $id)->first();
        if (!$subtype) {
            return response()->json(['message' => "Подтипа не существует", 'status' => 404], 404);
        }
        Subtype::where('subtype_id', '=', $id)->delete();
        return response()->json(['message' => 'Успешно удалено', 'status' => 200], 200);
    }
}
