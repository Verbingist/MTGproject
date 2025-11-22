<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Tournament extends Model
{
    protected $appends = ['signed'];

    public function getSignedAttribute()
    {
        if (Auth::check()) {
            $signed = Users_tournament::where('user_id', '=', Auth::id())->where('tournament_id', '=', $this->tournament_id)->first();
            if ($signed) {
                return 'Записан';
            }
        }
        return "Не записан";
    }
}
