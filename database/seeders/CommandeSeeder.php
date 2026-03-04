<?php

namespace Database\Seeders;

use App\Models\Burger;
use App\Models\Commande;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommandeSeeder extends Seeder
{
    public function run(): void
    {
        $clients = User::role('client')->get();
        $burgers = Burger::where('is_archived', false)->where('stock', '>', 0)->get();

        if ($clients->isEmpty() || $burgers->isEmpty()) {
            return;
        }

        foreach ($clients->take(3) as $client) {
            $burger = $burgers->random();
            $quantite = min(max(1, random_int(1, 3)), max(1, $burger->stock));
            $total = $burger->prix * $quantite;

            $commande = Commande::create([
                'user_id' => $client->id,
                'statut' => 'en_attente',
                'total' => $total,
                'is_paid' => false,
            ]);

            $commande->burgers()->attach($burger->id, [
                'quantite' => $quantite,
                'prix_unitaire' => $burger->prix,
            ]);

            $burger->decrement('stock', $quantite);

            if (random_int(0, 1) === 1) {
                $commande->update([
                    'statut' => 'payee',
                    'is_paid' => true,
                    'paid_at' => now(),
                ]);

                $commande->paiement()->create([
                    'montant' => $total,
                    'date_paiement' => now(),
                ]);
            }
        }
    }
}
