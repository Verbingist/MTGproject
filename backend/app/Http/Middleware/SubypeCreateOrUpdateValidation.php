<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\ValidationException;

class SubypeCreateOrUpdateValidation
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $request->validate([
                'subtype_name' => 'required|unique:subtypes,subtype_name'
            ]);
        } catch (ValidationException $error) {
            return response()->json(['message' => 'Введены некорректные данные', 'status' => 400], 400);
        }
        return $next($request);
    }
}
