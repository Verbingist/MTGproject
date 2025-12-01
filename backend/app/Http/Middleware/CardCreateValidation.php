<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\ValidationException;

class CardCreateValidation
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $request->validate([
                'card_name' => 'required|unique:cards,card_name',
                'price' => 'nullable|decimal:0,2|min:0.01',
                'text_rules' => 'nullable',
                'illustration_author' => 'required',
                'flavor_text' => 'nullable',
                'image_href' => 'nullable|url',
                'power' => 'nullable|numeric|min:0',
                'thoughtness' => 'nullable|numeric|min:0',
                'keywords' => 'nullable|array|min:0|distinct',
                'keywords.*' => 'exists:keywords,keyword_name',
                'types' => 'required|array|min:1|distinct',
                'types.*' => 'exists:types,type_name',
                'subtypes' => 'nullable|array|min:0|distinct',
                'subtypes.*' => 'exists:subtypes,subtype_name',
                'supertypes' => 'nullable|array|min:0|distinct',
                'supertypes.*' => 'exists:supertypes,supertype_name',
                'white_mana' => 'nullable|numeric|min:0',
                'blue_mana' => 'nullable|numeric|min:0',
                'black_mana' => 'nullable|numeric|min:0',
                'red_mana' => 'nullable|numeric|min:0',
                'green_mana' => 'nullable|numeric|min:0',
                'colorless_mana' => 'nullable|numeric|min:0',
            ]);
        } catch (ValidationException $error) {
            return response()->json(['message' => 'Введены некорректные данные', 'errors' => $error->errors(), 'status' => 400], 400);
        }
        return $next($request);
    }
}
