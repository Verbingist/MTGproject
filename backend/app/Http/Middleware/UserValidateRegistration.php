<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\ValidationException;

class UserValidateRegistration
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $request->validate([
                'first_name' => 'required|alpha|min:4',
                'last_name' => 'required|alpha|min:4',
                'email' => 'required|email|unique:users',
                'login' => 'required|unique:users|min:4',
                'password' => 'required|min:4',
                'age' => 'nullable|numeric|between:9,91',
                'rating' => 'nullable|in:Bronze,Silver,Gold,Mythic'
            ]);
        } catch (ValidationException $error) {
            return response()->json(['message' => 'Введены некорректные данные', 'status' => 400], 400);
        }

        return $next($request);
    }
}
