<?php

namespace App\Http\Middleware;

use App\Models\Deck;
use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsOwnDeck
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Не ваша колода, доступ запрещен', 'isOwnDeck' => false, 'status' => 200], 200);
        }
        $isOwnDeck = Deck::where('user_id', '=', Auth::id())
            ->where('deck_id', '=', $request->route('id'))->first();
        if (!$isOwnDeck) {
            return response()->json(['message' => 'Не ваша колода, доступ запрещен', 'isOwnDeck' => false, 'status' => 200], 200);
        }
        return $next($request);
    }
}
