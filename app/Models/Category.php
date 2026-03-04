<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Ajoute ceci pour permettre l'enregistrement via Category::create()
    protected $fillable = ['nom'];

    public function burgers()
    {
        return $this->hasMany(Burger::class);
    }
}
