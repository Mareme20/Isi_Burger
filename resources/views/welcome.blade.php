<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'ISI BURGER') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Manrope', sans-serif;
            margin: 0;
            min-height: 100vh;
            color: #2f1a12;
            background:
                radial-gradient(circle at 15% 20%, #fff7e6 0, #fff7e6 8%, transparent 35%),
                radial-gradient(circle at 82% 16%, #ffd6a9 0, #ffd6a9 10%, transparent 30%),
                linear-gradient(130deg, #fff1dc, #ffd2a4 45%, #f39a4a);
        }
        .hero { min-height: 100vh; display: flex; align-items: center; }
        .hero-card {
            background: rgba(255, 250, 244, .93);
            border: 1px solid #f1d4b3;
            border-radius: 1.4rem;
            box-shadow: 0 20px 40px rgba(72, 28, 12, .14);
        }
        .brand {
            font-family: 'Bebas Neue', sans-serif;
            letter-spacing: .04em;
            font-size: clamp(2.2rem, 5vw, 4rem);
            line-height: .92;
        }
        .hero-btn {
            border: 0;
            border-radius: .9rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .08em;
            padding: .76rem 1.1rem;
        }
        .hero-btn-primary {
            color: #fff;
            background: linear-gradient(90deg, #e85d04, #ff8f2b);
            box-shadow: 0 10px 20px rgba(201, 73, 0, .28);
        }
    </style>
</head>
<body>
    <div class="container hero py-4">
        <div class="hero-card p-4 p-lg-5 w-100">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
                <a href="/" class="d-inline-flex align-items-center gap-2 text-decoration-none text-dark">
                    <x-application-logo style="width: 46px; height: 46px;" />
                    <span class="fw-bold">{{ config('app.name', 'ISI BURGER') }}</span>
                </a>
                <div class="d-flex gap-2">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-dark rounded-3">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-dark rounded-3">Connexion</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn hero-btn hero-btn-primary">Inscription</a>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="row g-4 align-items-center">
                <div class="col-12 col-lg-7">
                    <h1 class="brand mb-3">ISI BURGER<br>Saveur. Rapidite. Qualite.</h1>
                    <p class="text-secondary mb-4" style="max-width: 48ch;">
                        Commandez vos burgers preferes, suivez vos commandes en temps reel et gerer votre activite
                        facilement depuis une interface moderne et rapide.
                    </p>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('login') }}" class="btn hero-btn hero-btn-primary">Commencer</a>
                        <a href="{{ route('catalogue.index') }}" class="btn btn-outline-dark hero-btn">Voir le catalogue</a>
                    </div>
                </div>
                <div class="col-12 col-lg-5">
                    <div class="p-4 rounded-4 border" style="background:#fff7ed;border-color:#f0cfac !important;">
                        <p class="text-uppercase small fw-bold text-secondary mb-2">Plateforme</p>
                        <ul class="mb-0 ps-3">
                            <li>Catalogue produits avec filtres</li>
                            <li>Gestion commandes et paiements</li>
                            <li>Espace client et gestionnaire</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
