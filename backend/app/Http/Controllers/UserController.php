<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Tournament;
use App\Models\Users_tournament;
use App\Models\Users_cards;
use App\Models\Card;

class UserController extends Controller
{
    public function registration(Request $request)
    {
        User::insert([
            'first_name' => $request->firstname,
            'last_name' => $request->secondname,
            'email' => $request->email,
            'login' => $request->login,
            'password' => Hash::make($request->password),
            'age' => $request->age,
            'rating' => $request->rating
        ]);
        return response()->json(['message' => 'Успешная регистрация', 'status' => 200], 200);
    }
    public function login(Request $request)
    {
        if (Auth::attempt($request->only('login', 'password'))) {
            return response()->json(['message' => 'Успешный вход'], 200);
        }
        return response()->json(['message' => 'Неверные данные'], 401);
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json(['message' => 'Успешный выход'], 200);
    }
    public function getUser($id)
    {
        $userLogin = User::where('id', '=', $id)->value('login');
        if (!$userLogin)
            return response()->json(['message' => 'Пользователь не найден', 'status' => 404], 404);
        return response()->json(['login' => $userLogin, 'status' => 200], 200);
    }
    public function getUsers()
    {
        $userLogin = User::select('login')->orderBy('login')->paginate(8);
        return response()->json(['logins' => $userLogin, 'status' => 200], 200);
    }
    public function updateUser(Request $request, $id = 0)
    {
        if ($request->user()->cannot('update', User::class)) {
            return response()->json(['message' => "У вас недостаточно прав для этого действия", 'status' => 404], 404);
        }
        if (!$id) {
            $id = Auth::id();
        }
        $user = User::where('id', '=', $id)->first();
        if (!$user) {
            return response()->json(['message' => "Юзера не существует", 'status' => 404], 404);
        }
        $updated_data = [
            "first_name" => $request->first_name ?? $user->first_name,
            "last_name" => $request->last_name ?? $user->last_name,
            "login" => $request->login ?? $user->login,
            "age" => $request->age ?? $user->age
        ];
        User::where('id', '=', $id)->update($updated_data);
        return response()->json(['message' => "Успешно обновлено", 'status' => 200], 200);
    }
    public function deleteUser($id)
    {
        if (auth()->user()->cannot('delete', User::class)) {
            return response()->json(['message' => "У вас недостаточно прав для этого действия", 'status' => 404], 404);
        }
        $userLogin = User::where('id', '=', $id)->delete();
        if (!$userLogin)
            return response()->json(['message' => 'Пользователь не был удален', 'status' => 404], 404);
        return response()->json(['login' => $userLogin, 'status' => 200], 200);
    }
    public function signUpTournament($tournamentId)
    {
        $tounament = Tournament::where('tournament_id', '=', $tournamentId)->first();
        if (!$tounament)
            return response()->json(['message' => 'Турнир не существует', 'status' => 404], 404);
        Users_tournament::insert([
            'user_id' => Auth::id(),
            'tournament_id' => $tournamentId
        ]);
        return response()->json(['message' => 'Успешная запись на турнир', 'status' => 200], 200);
    }
    public function signDownTournament($tournamentId)
    {
        $tounament = Tournament::where('tournament_id', '=', $tournamentId)->first();
        if (!$tounament)
            return response()->json(['message' => 'Турнир не существует', 'status' => 404], 404);
        $deleted = Users_tournament::where([
            'user_id' => Auth::id(),
            'tournament_id' => $tournamentId
        ])->delete();
        if (!$deleted) {
            return response()->json(['message' => 'Вы не записаны на этот турнир', 'status' => 400], 400);
        }
        return response()->json(['message' => 'Запись успешно удалена', 'status' => 200], 200);
    }
    public function addCardToCollection(Request $request)
    {
        $card_id = Card::where('card_name', '=', $request->card_name)->value('card_id');
        if (!$card_id)
            return response()->json(['message' => 'Карта не существует', 'status' => 404], 404);
        $card_in_collection = Users_cards::where([
            'user_id' => Auth::id(),
            'card_id' => $card_id
        ])->first();
        if ($card_in_collection) {
            return response()->json(['message' => 'Карта уже существует', 'status' => 404], 404);
        }
        Users_cards::insert([
            'user_id' => Auth::id(),
            'card_id' => $card_id
        ]);
        return response()->json(['message' => 'Карта успешно добавлена', 'status' => 200], 200);
    }
    public function removeCardFromCollection(Request $request)
    {
        $card_id = Card::where('card_name', '=', $request->card_name)->value('card_id');
        if (!$card_id)
            return response()->json(['message' => 'Карта не существует', 'status' => 404], 404);
        $card_in_collection = Users_cards::where([
            'user_id' => Auth::id(),
            'card_id' => $card_id
        ])->first();
        if (!$card_in_collection) {
            return response()->json(['message' => 'Карты нет в коллекции', 'status' => 404], 404);
        }
        Users_cards::where([
            'user_id' => Auth::id(),
            'card_id' => $card_id
        ])->delete();
        return response()->json(['message' => 'Карта успешно удалена', 'status' => 200], 200);
    }
    public function getCollection($user_id)
    {
        $user = User::where([
            'id' => $user_id,
        ])->first();
        if (!$user) {
            return response()->json(['message' => 'Пользователя не существует', 'status' => 404], 404);
        }
        $collection = $user->getCards()->select('card_name')->paginate(30);
        return response()->json(['data' => $collection, 'status' => 200], 200);
    }
}