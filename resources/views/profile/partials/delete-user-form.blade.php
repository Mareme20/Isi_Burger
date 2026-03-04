<section>
    <header class="mb-3">
        <h2 class="h5 fw-bold mb-1 text-danger">Suppression du compte</h2>
        <p class="text-secondary small mb-0">Action irreversible: toutes vos donnees seront supprimees definitivement.</p>
    </header>

    <button type="button" class="btn btn-outline-danger rounded-3" data-bs-toggle="modal" data-bs-target="#confirmUserDeletionModal">
        Supprimer mon compte
    </button>

    <div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-labelledby="confirmUserDeletionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form method="POST" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmUserDeletionModalLabel">Confirmer la suppression</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>

                    <div class="modal-body">
                        <p class="text-secondary small">
                            Cette action supprime definitivement votre compte et tout son contenu.
                            Saisissez votre mot de passe pour confirmer.
                        </p>

                        <label for="delete_account_password" class="form-label fw-semibold">Mot de passe</label>
                        <input id="delete_account_password" name="password" type="password" class="form-control" placeholder="Votre mot de passe">
                        @if ($errors->userDeletion->get('password'))
                            <div class="text-danger small mt-1">{{ $errors->userDeletion->first('password') }}</div>
                        @endif
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-danger">Supprimer definitivement</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if ($errors->userDeletion->isNotEmpty())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modalEl = document.getElementById('confirmUserDeletionModal');
                if (!modalEl || !window.bootstrap) return;
                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            });
        </script>
    @endif
</section>
