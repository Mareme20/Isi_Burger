<?php

namespace Database\Seeders;

use App\Models\Burger;
use App\Models\Category;
use Illuminate\Database\Seeder;

class BurgerSeeder extends Seeder
{
    public function run(): void
    {
        $burgers = [
            ['nom' => 'Double Cheese', 'prix' => 4500, 'description' => 'Deux steaks, cheddar, sauce maison.', 'stock' => 20, 'category' => 'Classiques'],
            ['nom' => 'Bacon Deluxe', 'prix' => 5200, 'description' => 'Bacon grillé, oignons caramélisés.', 'stock' => 16, 'category' => 'Signature'],
            ['nom' => 'Chicken Crispy', 'prix' => 4300, 'description' => 'Poulet croustillant, mayo citronnée.', 'stock' => 18, 'category' => 'Poulet'],
            ['nom' => 'Veggie Green', 'prix' => 4000, 'description' => 'Galette végétale, légumes frais.', 'stock' => 12, 'category' => 'Végétarien'],
            ['nom' => 'Smoky BBQ', 'prix' => 5500, 'description' => 'Sauce BBQ fumée, pickles.', 'stock' => 15, 'category' => 'Signature'],
            ['nom' => 'Classic Burger', 'prix' => 3800, 'description' => 'Le burger simple et efficace.', 'stock' => 25, 'category' => 'Classiques'],
        ];

        foreach ($burgers as $data) {
            $categoryId = Category::where('nom', $data['category'])->value('id');
            if (! $categoryId) {
                continue;
            }

            Burger::updateOrCreate(
                ['nom' => $data['nom']],
                [
                    'prix' => $data['prix'],
                    'description' => $data['description'],
                    'stock' => $data['stock'],
                    'image' => null,
                    'category_id' => $categoryId,
                    'is_archived' => false,
                ]
            );
        }
    }
}
