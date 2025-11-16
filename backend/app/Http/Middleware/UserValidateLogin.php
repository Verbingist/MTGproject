<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\ValidationException;

class UserValidateLogin
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $request->validate([
                'login' => 'required',
                'password' => 'required|min:4',
            ]);
        } catch (ValidationException $error) {
            return response()->json(['message' => 'Введены некорректные данные', 'status' => 401], 401);
        }
        return $next($request);
    }
}
