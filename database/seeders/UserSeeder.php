<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $gestionnaire = User::firstOrCreate(
            ['email' => 'gestionnaire@isiburger.test'],
            [
                'name' => 'Gestionnaire ISI Burger',
                'password' => 'password',
                'email_verified_at' => now(),
            ]
        );
        $gestionnaire->syncRoles(['gestionnaire']);

        $client = User::firstOrCreate(
            ['email' => 'client@isiburger.test'],
            [
                'name' => 'Client Démo',
                'password' => 'password',
                'email_verified_at' => now(),
            ]
        );
        $client->syncRoles(['client']);

        User::factory(5)->create()->each(function (User $user): void {
            $user->syncRoles(['client']);
        });
    }
}
