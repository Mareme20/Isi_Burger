<div class="cat-cart-card">
    <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
        <div>
            <h2 class="h5 fw-bold mb-1">Votre panier</h2>
            <p class="text-secondary small mb-0"><span data-panier-count>{{ $panier['count'] }}</span> article(s) selectionne(s).</p>
        </div>

        @if($panier['count'] > 0)
            <form method="POST" action="{{ route('panier.clear') }}" data-panier-form>
                @csrf
                <button type="submit" class="btn btn-sm btn-light border rounded-pill">Vider</button>
            </form>
        @endif
    </div>

    @if($errors->has('items'))
        <div class="auth-flash auth-flash-error">{{ $errors->first('items') }}</div>
    @endif

    @if($panier['items']->isEmpty())
        <p class="text-secondary mb-0">Votre panier est vide. Ajoutez un burger pour voir ici le resume de votre commande.</p>
    @else
        <div class="d-grid gap-3">
            @foreach($panier['items'] as $ligne)
                @php($burgerPanier = $ligne['burger'])

                <article class="cat-cart-item">
                    <div class="d-flex justify-content-between gap-3">
                        <div>
                            <p class="fw-bold mb-1">{{ $burgerPanier->nom }}</p>
                            <p class="small text-secondary mb-1">{{ number_format($burgerPanier->prix, 0, ',', ' ') }} FCFA l'unite</p>
                            <p class="small mb-0" style="color:#bf4600;font-weight:800;">Sous-total: {{ number_format($ligne['subtotal'], 0, ',', ' ') }} FCFA</p>
                        </div>

                        <form method="POST" action="{{ route('panier.remove', $burgerPanier) }}" data-panier-form>
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">Supprimer</button>
                        </form>
                    </div>

                    <form method="POST" action="{{ route('panier.update', $burgerPanier) }}" class="d-flex gap-2 align-items-end flex-wrap mt-3" data-panier-form>
                        @csrf
                        <div>
                            <label for="cart_qty_{{ $burgerPanier->id }}" class="form-label small fw-semibold">Quantite</label>
                            <input
                                id="cart_qty_{{ $burgerPanier->id }}"
                                type="number"
                                name="quantite"
                                min="0"
                                max="{{ $burgerPanier->stock }}"
                                value="{{ $ligne['quantite'] }}"
                                class="form-control cat-input"
                                style="max-width:110px;"
                            >
                        </div>
                        <button type="submit" class="btn btn-light border rounded-3">Mettre a jour</button>
                        <p class="small text-secondary mb-0">Mettre 0 pour retirer cet article.</p>
                    </form>
                </article>
            @endforeach
        </div>

        <div class="cat-cart-total d-flex justify-content-between align-items-center mt-3">
            <span>Total</span>
            <strong>{{ number_format($panier['total'], 0, ',', ' ') }} FCFA</strong>
        </div>

        @auth
            <form method="POST" action="{{ route('commandes.store') }}" class="mt-3">
                @csrf
                <button type="submit" class="cat-btn w-100">Valider ma commande</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="cat-btn d-block text-center text-decoration-none mt-3">Se connecter pour commander</a>
        @endauth
    @endif
</div>
