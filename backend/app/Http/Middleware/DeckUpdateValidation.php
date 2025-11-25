<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\ValidationException;

class DeckUpdateValidation
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $request->validate([
                'deck_name' => 'min:4|unique:decks',
                'format_name' => 'exists:formats,format_name',
                'power_level' => 'nullable|numeric|min:0|max:5',
                'commander_card_name' => 'nullable|exists:cards,card_name'
            ]);
        } catch (ValidationException $error) {
            return response()->json(['message' => 'Введены некорректные данные', 'status' => 400], 400);
        }
        return $next($request);
    }
}
