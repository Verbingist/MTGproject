<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Set;

class SetController extends Controller
{
    public function getSet($id)
    {
        $set = Set::where('set_id', '=', $id)->first();
        return response()->json(['set' => $set, 'status' => 200], 200);
    }
    public function getSets()
    {
        $sets = Set::orderBy('set_name')->paginate(8);
        return response()->json(['set' => $sets, 'status' => 200], 200);
    }
    public function createSet()
    {
        $sets = Set::orderBy('set_name')->paginate(8);
        return response()->json(['set' => $sets, 'status' => 200], 200);
    }
    public function updateSet()
    {

    }
    public function deleteSet()
    {

    }
    public function addCardToSet()
    {

    }
    public function removeCardToSet()
    {

    }
}
