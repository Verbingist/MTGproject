<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\ValidationException;

class TypeCreateOrUpdateValidation
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $request->validate([
                'type_name' => 'required|unique:types,type_name'
            ]);
        } catch (ValidationException $error) {
            return response()->json(['message' => 'Введены некорректные данные', 'status' => 400], 400);
        }
        return $next($request);
    }
}
