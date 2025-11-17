<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tournament;

class TournamentController extends Controller
{
    public function getTournament($id)
    {
        $tournament = Tournament::where('tournament_id', '=', $id)->first();
        if (!$tournament)
            return response()->json(['message' => 'Турнир не найден', 'status' => 404], 404);
        return response()->json(['tournament' => $tournament, 'status' => 200], 200);
    }
    public function getTournaments()
    {
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
    public function updateTournament(Request $request)
    {
        if ($request->user()->cannot('update', Tournament::class)) {
            return response()->json(['message' => "У вас недостаточно прав для этого действия", 'status' => 404], 404);
        }
        $tournament = Tournament::where('tournament_name', '=', $request->tournament_name)->first();
        if (!$tournament) {
            return response()->json(['message' => "Турнира не существует", 'status' => 404], 404);
        }
        $updated_data = [
            "deck_name" => $request->tournament_name ?? $tournament->tournament_name,
            "format_name" => $request->tournament_description ?? $tournament->tournament_description,
            "power_level" => $request->format_name ?? $tournament->format_name,
            "tournament_date" => $request->tournament_date ?? $tournament->tournament_date,
            "status" => $request->status ?? $tournament->status,
        ];
        Tournament::where('tournament_name', '=', $tournament->tournament_name)->update($updated_data);
        return response()->json(['message' => "Успешно обновлено", 'status' => 200], 200);
    }
    public function deleteTounament(Request $request)
    {
        if ($request->user()->cannot('delete', Tournament::class)) {
            return response()->json(['message' => "У вас недостаточно прав для этого действия", 'status' => 404], 404);
        }
        $tournament = Tournament::where('tournament_name', '=', $request->tournament_name)->first();
        if (!$tournament) {
            return response()->json(['message' => "Турнира не существует", 'status' => 404], 404);
        }
        Tournament::where('tournament_name', '=', $request->tournament_name)->delete();
        return response()->json(['message' => 'Успешно удалено', 'status' => 200], 200);
    }
}