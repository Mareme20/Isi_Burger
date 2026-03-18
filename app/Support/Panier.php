<?php

namespace App\Support;

use App\Models\Burger;
use Illuminate\Support\Collection;

class Panier
{
    private const SESSION_KEY = 'panier.items';

    public static function items(): Collection
    {
        return collect(session(self::SESSION_KEY, []))
            ->mapWithKeys(fn ($quantite, $burgerId) => [(int) $burgerId => (int) $quantite])
            ->filter(fn (int $quantite) => $quantite > 0);
    }

    public static function quantity(int $burgerId): int
    {
        return (int) self::items()->get($burgerId, 0);
    }

    public static function update(Burger $burger, int $quantite): void
    {
        $items = self::items();

        if ($quantite <= 0) {
            $items->forget($burger->id);
        } else {
            $items->put($burger->id, $quantite);
        }

        session([self::SESSION_KEY => $items->all()]);
    }

    public static function remove(int $burgerId): void
    {
        $items = self::items();
        $items->forget($burgerId);

        session([self::SESSION_KEY => $items->all()]);
    }

    public static function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    public static function summary(): array
    {
        $items = self::items();

        if ($items->isEmpty()) {
            return [
                'items' => collect(),
                'total' => 0,
                'count' => 0,
            ];
        }

        $burgers = Burger::with('category')
            ->whereIn('id', $items->keys())
            ->where('is_archived', false)
            ->where('stock', '>', 0)
            ->get()
            ->keyBy('id');

        $normalized = collect();

        foreach ($items as $burgerId => $quantite) {
            $burger = $burgers->get($burgerId);

            if (! $burger) {
                continue;
            }

            $finalQuantity = min($quantite, (int) $burger->stock);

            if ($finalQuantity <= 0) {
                continue;
            }

            $normalized->push([
                'burger' => $burger,
                'quantite' => $finalQuantity,
                'subtotal' => $burger->prix * $finalQuantity,
            ]);
        }

        session([
            self::SESSION_KEY => $normalized
                ->mapWithKeys(fn (array $ligne) => [$ligne['burger']->id => $ligne['quantite']])
                ->all(),
        ]);

        return [
            'items' => $normalized,
            'total' => $normalized->sum('subtotal'),
            'count' => $normalized->sum('quantite'),
        ];
    }
}
