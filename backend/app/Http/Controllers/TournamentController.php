<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tournament;
use Illuminate\Support\Facades\Auth;

class TournamentController extends Controller
{
    public function getTournament($id)
    {
        $tournament = Tournament::where('tournament_id', '=', $id)->first();
        if (!$tournament)
            return response()->json(['message' => 'Турнир не найден', 'status' => 404], 404);
        return response()->json(['tournament' => $tournament, 'status' => 200], 200);
    }
    public function getTournaments(Request $request)
    {
        $search = $request->query('search');
        if ($search) {
            $tournaments = Tournament::where('tournament_name', 'like', '%' . $search . '%')->orderBy('tournament_name')->paginate(8);
            return response()->json(['tournaments' => $tournaments, 'status' => 200], 200);
        }
        $tournaments = Tournament::paginate(8);
        return response()->json(['tournaments' => $tournaments, 'status' => 200], 200);
    }
    public function createTournament(Request $request)
    {
        if ($request->user()->cannot('create', Tournament::class)) {
            return response()->json(['message' => "У вас недостаточно прав для этого действия", 'status' => 404], 404);
        }
        $tournament = $request->only('tournament_name', 'tournament_description', 'format_name', 'tournament_date', 'status');
        Tournament::insert($tournament);
        return response()->json(['message' => "Успешно добавлено", 'status' => 200], 200);
    }
    public function updateTournament(Request $request, $id)
    {
        if ($request->user()->cannot('update', Tournament::class)) {
            return response()->json(['message' => "У вас недостаточно прав для этого действия", 'status' => 404], 404);
        }
        $tournament = Tournament::where('tournament_id', '=', $id)->first();
        if (!$tournament) {
            return response()->json(['message' => "Турнира не существует", 'status' => 404], 404);
        }
        $updated_data = [
            "tournament_name" => $request->tournament_name ?? $tournament->tournament_name,
            "tournament_description" => $request->tournament_description ?? $tournament->tournament_description,
            "format_name" => $request->format_name ?? $tournament->format_name,
            "tournament_date" => $request->tournament_date ?? $tournament->tournament_date,
            "status" => $request->status ?? $tournament->status,
        ];
        Tournament::where('tournament_id', '=', $id)->update($updated_data);
        return response()->json(['message' => "Успешно обновлено", 'status' => 200], 200);
    }
    public function deleteTournament($id)
    {
        if (Auth::user()->cannot('delete', Tournament::class)) {
            return response()->json(['message' => "У вас недостаточно прав для этого действия", 'status' => 404], 404);
        }
        $tournament = Tournament::where('tournament_id', '=', $id)->first();
        if (!$tournament) {
            return response()->json(['message' => "Турнира не существует", 'status' => 404], 404);
        }
        Tournament::where('tournament_id', '=', $id)->delete();
        return response()->json(['message' => 'Успешно удалено', 'status' => 200], 200);
    }
}