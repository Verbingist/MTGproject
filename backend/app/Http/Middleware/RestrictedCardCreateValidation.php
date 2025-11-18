<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\ValidationException;

class RestrictedCardCreateValidation
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $request->validate([
                'card_name' => 'required|exists:cards,card_name',
                'format_name' => 'required|exists:formats,format_name', 
                'restriction_type' => 'required|in:Banned,Restricted,Not legal', 
                'restriction_description' => 'nullable', 
                'date_of_restriction' => 'nullable|date|before:tomorrow'
            ]);
        } catch (ValidationException $error) {
            return response()->json(['message' => 'Введены некорректные данные', 'status' => 400], 400);
        }
        return $next($request);
    }
}
