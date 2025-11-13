<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\Tournament;
use App\Models\Users_tournament;
use App\Models\Users_cards;
use App\Models\Card;

class UserController extends Controller
{
    public function registration(Request $request)
    {
        try {
            $request->validate([
                'firstname' => 'required|alpha|min:4',
                'secondname' => 'required|alpha|min:4',
                'email' => 'required|email|unique:users',
                'login' => 'required|unique:users|min:4',
                'password' => 'required|min:4',
                'age' => 'nullable|numeric|between:9,91',
                'rating' => 'nullable|in:Bronze,Silver,Gold,Mythic'
            ]);
        } catch (ValidationException $error) {
            return response()->json(['message' => 'Введены некорректные данные', 'status' => 400], 400);
        }

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
        try {
            $validated = $request->validate([
                'login' => 'required',
                'password' => 'required|min:4',
            ]);
        } catch (ValidationException $error) {
            return response()->json(['message' => 'Введены некорректные данные', 'status' => 401], 401);
        }

        if (Auth::attempt($validated)) {
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
        $userLogin = User::select('login')->paginate(8);
        return response()->json(['logins' => $userLogin, 'status' => 200], 200);
    }
    public function updateUser(Request $request, $id)
    {
        try {
            $request->validate([
                'firstname' => 'alpha|min:4',
                'secondname' => 'alpha|min:4',
                'login' => 'unique:users|min:4',
                'age' => 'nullable|numeric|between:9,91',
            ]);
        } catch (ValidationException $error) {
            return response()->json(['message' => 'Введены некорректные данные', 'status' => 400], 400);
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
        if (Auth::check()) {
            Users_tournament::insert([
                'user_id' => Auth::id(),
                'tournament_id' => $tournamentId
            ]);
            return response()->json(['message' => 'Успешная запись на турнир', 'status' => 200], 200);
        }
        return response()->json(['message' => 'Вы не аутентифицированы', 'status' => 401], 401);
    }
    public function signDownTournament($tournamentId)
    {
        $tounament = Tournament::where('tournament_id', '=', $tournamentId)->first();
        if (!$tounament)
            return response()->json(['message' => 'Турнир не существует', 'status' => 404], 404);
        if (Auth::check()) {
            $deleted = Users_tournament::where([
                'user_id' => Auth::id(),
                'tournament_id' => $tournamentId
            ])->delete();
            if (!$deleted) {
                return response()->json(['message' => 'Вы не записаны на этот турнир', 'status' => 400], 400);
            }
            return response()->json(['message' => 'Запись успешно удалена', 'status' => 200], 200);
        }
        return response()->json(['message' => 'Вы не аутентифицированы', 'status' => 401], 401);
    }
    public function addCardToCollection(Request $request)
    {
        $card_id = Card::where('card_name', '=', $request->card_name)->value('card_id');
        if (!$card_id)
            return response()->json(['message' => 'Карта не существует', 'status' => 404], 404);
        if (Auth::check()) {
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
        return response()->json(['message' => 'Вы не аутентифицированы', 'status' => 401], 401);
    }
    public function removeCardFromCollection(Request $request)
    {
        $card_id = Card::where('card_name', '=', $request->card_name)->value('card_id');
        if (!$card_id)
            return response()->json(['message' => 'Карта не существует', 'status' => 404], 404);
        if (Auth::check()) {
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
        return response()->json(['message' => 'Вы не аутентифицированы', 'status' => 401], 401);
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