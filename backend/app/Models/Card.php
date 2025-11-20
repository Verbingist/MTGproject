<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Cards_subtypes;
use App\Models\Subtype;
use App\Models\Type;
use App\Models\Cards_types;
use App\Models\Supertype;
use App\Models\Cards_supertypes;
use App\Models\Keyword;
use App\Models\Cards_keywords;
use App\Models\Mana_value;

class Card extends Model
{
    protected $primaryKey = 'card_id';
    public function types()
    {
        return $this->belongsToMany(Type::class, Cards_types::class, 'card_id', 'type_id');
    }
    public function subtypes()
    {
        return $this->belongsToMany(Subtype::class, Cards_subtypes::class, 'card_id', 'subtype_id');
    }
    public function supertypes()
    {
        return $this->belongsToMany(Supertype::class, Cards_supertypes::class, 'card_id', 'supertype_id');
    }
    public function keywords()
    {
        return $this->belongsToMany(Keyword::class, Cards_keywords::class, 'card_id', 'keyword_id');
    }
    public function mana_value()
    {
        return $this->hasOne(Mana_value::class, 'card_id', 'card_id');
    }
}