@if (session('success'))
    <div class="auth-flash auth-flash-success d-flex justify-content-between align-items-start gap-2" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="auth-flash auth-flash-error d-flex justify-content-between align-items-start gap-2" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if ($errors->any())
    <div class="auth-flash auth-flash-error" role="alert">
        <p class="mb-0 fw-semibold">Veuillez corriger les erreurs suivantes :</p>
        <ul class="auth-errors-list">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
