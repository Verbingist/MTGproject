<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\ValidationException;

class LotUpdationValidation
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $request->validate([
                'lot_name' => 'nullable|min:4',
                'lot_description' => 'nullable',
                'price' => 'nullable|decimal:0,4',
            ]);
        } catch (ValidationException $error) {
            return response()->json(['message' => 'Введены некорректные данные', 'status' => 400], 400);
        }
        return $next($request);
    }
}
