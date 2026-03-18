<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'gestionnaire_id',
        'statut',
        'total',
        'is_paid',
        'paid_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gestionnaire()
    {
        return $this->belongsTo(User::class, 'gestionnaire_id');
    }

    public function burgers()
    {
        // On précise 'commande_burger' pour correspondre à Burger.php et à ta migration
        return $this->belongsToMany(Burger::class, 'commande_burger')
            ->withPivot('quantite', 'prix_unitaire')
            ->withTimestamps();
    }

    public function paiement()
    {
        return $this->hasOne(Paiement::class);
    }

    public function historiques()
    {
        return $this->hasMany(CommandeHistorique::class)->latest();
    }

    public function latestHistorique()
    {
        return $this->hasOne(CommandeHistorique::class)->latestOfMany();
    }
}
