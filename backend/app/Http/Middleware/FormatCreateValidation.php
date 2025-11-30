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
                'format_name' => 'required|unique:formats,format_name|min:4',
                'min_cards_in_deck' => 'required|numeric|min:40', 
                'max_cards_in_deck' => 'nullable|numeric|min:0', 
                'format_description' => 'required'
            ]);
        } catch (ValidationException $error) {
            return response()->json(['message' => 'Введены некорректные данные', 'status' => 400], 400);
        }
        return $next($request);
    }
}
