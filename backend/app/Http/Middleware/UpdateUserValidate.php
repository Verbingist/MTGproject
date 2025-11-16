<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\ValidationException;


class UpdateUserValidate
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $request->validate([
                'first_name' => 'alpha|min:4',
                'second_name' => 'alpha|min:4',
                'login' => 'unique:users|min:4',
                'age' => 'nullable|numeric|between:9,91',
            ]);
        } catch (ValidationException $error) {
            return response()->json(['message' => 'Введены некорректные данные', 'status' => 400], 400);
        }
        return $next($request);
    }
}