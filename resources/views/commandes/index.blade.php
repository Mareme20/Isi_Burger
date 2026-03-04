<x-app-layout>
    <x-slot name="header">
        <h1 class="h4 mb-0 fw-bold">Toutes les commandes</h1>
    </x-slot>

    <div class="app-section-card p-3 p-md-4">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Total</th>
                        <th>Statut</th>
                        <th>Paiement</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($commandes as $commande)
                    <tr>
                        <td>{{ $commande->id }}</td>
                        <td>{{ $commande->user->name }}</td>
                        <td>{{ number_format($commande->total, 0, ',', ' ') }} FCFA</td>
                        <td>{{ $commande->statut }}</td>
                        <td>{{ $commande->is_paid ? 'Payee' : 'Non payee' }}</td>
                        <td>
                            <a class="btn btn-sm btn-outline-dark" href="{{ route('admin.commandes.show', $commande) }}">Voir</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $commandes->links() }}
        </div>
    </div>
</x-app-layout>
