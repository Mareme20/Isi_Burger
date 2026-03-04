<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ISI Burger') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/js/app.js'])
    <style>
        :root {
            --auth-bg-a: #fff0d7;
            --auth-bg-b: #ffd5ae;
            --auth-bg-c: #f9a03f;
            --auth-dark: #31140e;
            --auth-card: #fffaf4;
            --auth-line: #f4d7b7;
            --auth-accent: #e85d04;
            --auth-accent-strong: #c44800;
            --auth-muted: #6f4b3e;
            --auth-ok: #1b7a46;
            --auth-error: #ab2d24;
        }

        body.auth-page {
            font-family: 'Manrope', sans-serif;
            min-height: 100vh;
            margin: 0;
            color: var(--auth-dark);
            background:
                radial-gradient(circle at 15% 20%, #fff6df 0, #fff6df 12%, transparent 42%),
                radial-gradient(circle at 80% 25%, #ffd3a9 0, #ffd3a9 10%, transparent 36%),
                linear-gradient(125deg, var(--auth-bg-a), var(--auth-bg-b) 42%, var(--auth-bg-c) 100%);
        }

        .auth-shell {
            min-height: 100vh;
            padding: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-frame {
            width: min(1120px, 100%);
            border-radius: 30px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, .55);
            background: rgba(255, 251, 245, .55);
            box-shadow: 0 28px 70px rgba(73, 31, 14, .22);
            display: grid;
            grid-template-columns: 1fr 1.05fr;
        }

        .auth-brand {
            padding: clamp(1.75rem, 4vw, 3.4rem);
            background:
                linear-gradient(180deg, rgba(255, 159, 67, .92), rgba(230, 87, 31, .96)),
                repeating-linear-gradient(-35deg, rgba(255, 255, 255, .12), rgba(255, 255, 255, .12) 10px, rgba(255, 255, 255, 0) 10px, rgba(255, 255, 255, 0) 22px);
            color: #fff9f3;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 2rem;
            position: relative;
            isolation: isolate;
        }

        .auth-brand::after {
            content: "";
            position: absolute;
            inset: auto -35% -25% auto;
            width: 78%;
            aspect-ratio: 1;
            border-radius: 50%;
            background: rgba(255, 240, 215, .22);
            z-index: -1;
        }

        .auth-logo {
            display: inline-flex;
            align-items: center;
            gap: .75rem;
            color: #fff;
            text-decoration: none;
        }

        .auth-logo-text {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(1.8rem, 2.8vw, 2.35rem);
            letter-spacing: .04em;
            line-height: 1;
        }

        .auth-brand h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(2.35rem, 4.4vw, 3.9rem);
            line-height: .96;
            letter-spacing: .01em;
            margin: 0;
            text-transform: uppercase;
            max-width: 10ch;
        }

        .auth-brand p {
            margin: .85rem 0 0;
            font-size: 1.02rem;
            color: rgba(255, 249, 242, .92);
            max-width: 33ch;
        }

        .auth-badges {
            display: flex;
            flex-wrap: wrap;
            gap: .45rem;
        }

        .auth-badges span {
            font-size: .77rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .09em;
            border: 1px solid rgba(255, 255, 255, .43);
            color: #fff8f1;
            padding: .44rem .65rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, .1);
        }

        .auth-content {
            padding: clamp(1.35rem, 4vw, 3rem);
            background: linear-gradient(170deg, rgba(255, 250, 244, .96), rgba(255, 244, 232, .95));
            backdrop-filter: blur(8px);
        }

        .auth-card {
            border: 1px solid var(--auth-line);
            border-radius: 24px;
            background: var(--auth-card);
            padding: clamp(1.15rem, 3vw, 2rem);
            box-shadow: 0 10px 24px rgba(73, 31, 14, .08);
        }

        .auth-card h2 {
            margin: 0 0 .3rem;
            font-size: clamp(1.3rem, 2.1vw, 1.75rem);
            font-weight: 800;
            letter-spacing: -.02em;
        }

        .auth-subtitle {
            margin: 0 0 1.15rem;
            color: var(--auth-muted);
            font-size: .95rem;
        }

        .auth-label {
            font-size: .84rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #6e3b26;
            margin-bottom: .42rem;
        }

        .auth-input,
        .auth-select {
            border: 1px solid #e7c7a8;
            background: #fffefb;
            border-radius: .92rem;
            color: var(--auth-dark);
            padding: .72rem .9rem;
            font-size: .95rem;
            transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
        }

        .auth-input:focus,
        .auth-select:focus {
            border-color: #f68b45;
            box-shadow: 0 0 0 .24rem rgba(232, 93, 4, .16);
            transform: translateY(-1px);
        }

        .auth-btn {
            border: 0;
            border-radius: .95rem;
            padding: .76rem 1.1rem;
            font-weight: 800;
            font-size: .92rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #fff;
            background: linear-gradient(90deg, var(--auth-accent), #ff8f2b);
            box-shadow: 0 10px 20px rgba(214, 79, 0, .29);
            transition: transform .18s ease, box-shadow .18s ease;
        }

        .auth-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 24px rgba(181, 68, 2, .34);
            background: linear-gradient(90deg, var(--auth-accent-strong), #ef7e1c);
            color: #fff;
        }

        .auth-link {
            color: #bf4600;
            text-decoration: none;
            font-weight: 700;
        }

        .auth-link:hover {
            color: #963700;
            text-decoration: underline;
        }

        .auth-check {
            border-color: #e8c39f;
        }

        .auth-check:checked {
            background-color: var(--auth-accent);
            border-color: var(--auth-accent);
        }

        .auth-flash {
            border-radius: 14px;
            border: 1px solid transparent;
            padding: .72rem .9rem;
            margin-bottom: .95rem;
            font-size: .91rem;
            font-weight: 600;
        }

        .auth-flash-success {
            border-color: #afdcbc;
            background: #ecf9f1;
            color: var(--auth-ok);
        }

        .auth-flash-error {
            border-color: #efc2be;
            background: #fdf1f0;
            color: var(--auth-error);
        }

        .auth-errors-list {
            margin: .5rem 0 0;
            padding-left: 1.2rem;
            font-weight: 500;
        }

        .auth-errors-list li + li {
            margin-top: .15rem;
        }

        .auth-inline-error {
            margin-top: .35rem;
            color: var(--auth-error);
            font-size: .82rem;
            font-weight: 600;
        }

        @media (max-width: 991.98px) {
            .auth-frame {
                grid-template-columns: 1fr;
            }

            .auth-brand {
                min-height: 280px;
            }

            .auth-brand h1 {
                max-width: 100%;
            }
        }
    </style>
</head>
<body class="auth-page">
    <div class="auth-shell">
        <main class="auth-frame">
            <section class="auth-brand">
                <a href="/" class="auth-logo">
                    <x-application-logo style="width: 44px; height: 44px;" />
                    <span class="auth-logo-text">{{ config('app.name', 'ISI Burger') }}</span>
                </a>

                <div>
                    <h1>Le burger qui marque les esprits</h1>
                    <p>Connectez-vous ou créez votre compte pour commander en quelques secondes et suivre vos menus en temps reel.</p>
                </div>

                <div class="auth-badges">
                    <span>Pret en 20 min</span>
                    <span>Paiement securise</span>
                    <span>Suivi commande live</span>
                </div>
            </section>

            <section class="auth-content">
                <div class="auth-card">
                    @include('layouts.flash-messages')
                    {{ $slot }}
                </div>
            </section>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
