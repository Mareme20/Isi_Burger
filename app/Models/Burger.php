<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Burger extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prix',
        'description',
        'image',
        'stock',
        'category_id',
        'is_archived'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function commandes()
    {
        // On précise 'commande_burger' car ta migration n'est pas par ordre alphabétique
        return $this->belongsToMany(Commande::class, 'commande_burger')
            ->withPivot('quantite', 'prix_unitaire')
            ->withTimestamps();
    }
}
