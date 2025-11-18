<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\ValidationException;

class FormatCreateValidation
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $request->validate([
                'min_cards_in_deck' => 'require|min:40', 
                'max_cards_in_deck' => 'nullable|min:0', 
                'format_description' => 'required'
            ]);
        } catch (ValidationException $error) {
            return response()->json(['message' => 'Введены некорректные данные', 'status' => 400], 400);
        }
        return $next($request);
    }
}
