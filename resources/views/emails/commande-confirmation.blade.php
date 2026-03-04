<h2>Confirmation de commande #{{ $commande->id }}</h2>

<p>Bonjour {{ $commande->user->name }},</p>

<p>Votre commande a bien ete enregistree chez <strong>ISI BURGER</strong>.</p>

<p><strong>Recapitulatif:</strong></p>
<ul>
    @foreach($commande->burgers as $burger)
        <li>
            {{ $burger->nom }} - x{{ $burger->pivot->quantite }}
            ({{ number_format($burger->pivot->prix_unitaire, 0, ',', ' ') }} FCFA)
        </li>
    @endforeach
</ul>

<p><strong>Total:</strong> {{ number_format($commande->total, 0, ',', ' ') }} FCFA</p>
<p><strong>Statut:</strong> En attente</p>

<p>Merci pour votre confiance.</p>

