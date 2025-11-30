<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\ValidationException;

class TournamentCreationValidation
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $request->validate([
                'tournament_name' => 'nullable',
                'tournament_description' => 'nullable',
                'format_name' => 'exists:formats,format_name',
                'tournament_date' => 'required|date|after:tomorrow',
                'status' => 'nullable|in:Запланирован,Отменен,Завершен'
            ]);
        } catch (ValidationException $error) {
            return response()->json(['message' => 'Введены некорректные данные', 'status' => 400], 400);
        }
        return $next($request);
    }
}
