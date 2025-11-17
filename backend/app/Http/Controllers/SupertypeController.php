<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supertype;

class SupertypeController extends Controller
{
    public function getSupertype($id)
    {
        $supertype = Supertype::where('supertype_id', '=', $id)->first();
        if (!$supertype)
            return response()->json(['message' => 'Супертип не найден', 'status' => 404], 404);
        return response()->json(['supertype' => $supertype, 'status' => 200], 200);
    }
    public function getSupertypes()
    {
        $supertypes = Supertype::paginate(8);
        return response()->json(['supertypes' => $supertypes, 'status' => 200], 200);
    }
    public function createSupertype(Request $request)
    {
        if ($request->user()->cannot('create', Supertype::class)) {
            return response()->json(['message' => "У вас недостаточно прав для этого действия", 'status' => 404], 404);
        }
        $supertype = $request->only('supertype_name');
        Supertype::insert($supertype);
        return response()->json(['message' => "Успешно добавлено", 'status' => 200], 200);
    }
    public function updateSupertype(Request $request)
    {
        if ($request->user()->cannot('update', Supertype::class)) {
            return response()->json(['message' => "У вас недостаточно прав для этого действия", 'status' => 404], 404);
        }
        $supertype = Supertype::where('supertype_name', '=', $request->supertype_name)->first();
        if (!$supertype) {
            return response()->json(['message' => "Супертипа не существует", 'status' => 404], 404);
        }
        $updated_data = [
            "supertype_name" => $request->supertype_name ?? $supertype->supertype_name,
        ];
        Supertype::where('supertype_name', '=', $supertype->supertype_name)->update($updated_data);
        return response()->json(['message' => "Успешно обновлено", 'status' => 200], 200);
    }
    public function deleteSupertype(Request $request)
    {
        if ($request->user()->cannot('delete', Supertype::class)) {
            return response()->json(['message' => "У вас недостаточно прав для этого действия", 'status' => 404], 404);
        }
        $supertype = Supertype::where('supertype_name', '=', $request->supertype_name)->first();
        if (!$supertype) {
            return response()->json(['message' => "Супертипа не существует", 'status' => 404], 404);
        }
        Supertype::where('supertype_name', '=', $request->supertype_name)->delete();
        return response()->json(['message' => 'Успешно удалено', 'status' => 200], 200);
    }
}
