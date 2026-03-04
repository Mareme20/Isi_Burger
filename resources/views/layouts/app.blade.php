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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite(['resources/js/app.js'])

    <style>
        :root {
            --app-bg: #fdf4ea;
            --app-bg-2: #ffe5c5;
            --app-sidebar: #2a120b;
            --app-sidebar-2: #3a170d;
            --app-panel: #fff9f2;
            --app-panel-line: #f3d7bb;
            --app-text: #2f1a12;
            --app-muted: #775043;
            --app-accent: #e85d04;
            --app-accent-2: #ff8a28;
            --app-ok: #1c7f49;
            --app-error: #a62b23;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Manrope', sans-serif;
            color: var(--app-text);
            background:
                radial-gradient(circle at 8% 10%, #fff4df 0, #fff4df 8%, transparent 36%),
                radial-gradient(circle at 85% 18%, #ffd8ac 0, #ffd8ac 9%, transparent 32%),
                linear-gradient(120deg, var(--app-bg), var(--app-bg-2) 52%, #ffc585);
        }

        .app-shell {
            min-height: 100vh;
            display: flex;
        }

        .app-sidebar {
            width: 286px;
            background: linear-gradient(180deg, var(--app-sidebar), var(--app-sidebar-2));
            color: #fff8f2;
            padding: 1.3rem;
            position: sticky;
            top: 0;
            height: 100vh;
            border-right: 1px solid rgba(255, 255, 255, .08);
        }

        .app-brand {
            display: flex;
            align-items: center;
            gap: .75rem;
            color: #fff8f2;
            text-decoration: none;
            margin-bottom: 1.35rem;
        }

        .app-brand-name {
            font-family: 'Bebas Neue', sans-serif;
            letter-spacing: .05em;
            font-size: 1.7rem;
            line-height: 1;
        }

        .app-brand-sub {
            font-size: .77rem;
            color: rgba(255, 236, 216, .82);
            text-transform: uppercase;
            letter-spacing: .08em;
            font-weight: 700;
        }

        .app-menu-title {
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: rgba(255, 232, 211, .65);
            margin-bottom: .65rem;
            font-weight: 800;
        }

        .app-menu-link {
            display: block;
            text-decoration: none;
            color: rgba(255, 241, 229, .9);
            border: 1px solid transparent;
            border-radius: .92rem;
            padding: .72rem .85rem;
            font-weight: 700;
            font-size: .9rem;
            margin-bottom: .45rem;
            transition: all .18s ease;
        }

        .app-menu-link:hover {
            border-color: rgba(255, 174, 121, .38);
            background: rgba(255, 255, 255, .09);
            color: #fff;
        }

        .app-menu-link.active {
            background: linear-gradient(90deg, var(--app-accent), var(--app-accent-2));
            color: #fff;
            box-shadow: 0 10px 20px rgba(0, 0, 0, .18);
        }

        .app-user {
            border: 1px solid rgba(255, 173, 121, .25);
            border-radius: 1rem;
            padding: .9rem;
            background: rgba(255, 255, 255, .05);
        }

        .app-user-name {
            font-weight: 800;
            font-size: .93rem;
            color: #fff7ed;
        }

        .app-user-role {
            font-size: .78rem;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: rgba(255, 227, 202, .7);
            margin-bottom: .65rem;
        }

        .app-logout {
            border-radius: .8rem;
            border: 1px solid rgba(255, 173, 121, .34);
            color: #fff0df;
            background: rgba(255, 255, 255, .05);
            font-size: .82rem;
            font-weight: 700;
        }

        .app-logout:hover {
            border-color: rgba(255, 196, 147, .5);
            color: #fff;
            background: rgba(255, 255, 255, .12);
        }

        .app-main {
            min-width: 0;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .app-topbar {
            border-bottom: 1px solid #f1cfac;
            background: rgba(255, 247, 236, .86);
            backdrop-filter: blur(7px);
        }

        .app-content {
            padding: 1.35rem;
        }

        .app-header {
            border: 1px solid var(--app-panel-line);
            border-radius: 1.1rem;
            background: linear-gradient(180deg, rgba(255, 250, 244, .96), rgba(255, 243, 229, .95));
            padding: .95rem 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 8px 18px rgba(72, 28, 12, .07);
        }

        .app-section-card {
            border: 1px solid var(--app-panel-line);
            border-radius: 1rem;
            background: var(--app-panel);
            box-shadow: 0 8px 18px rgba(72, 28, 12, .07);
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
            color: var(--app-ok);
        }

        .auth-flash-error {
            border-color: #efc2be;
            background: #fdf1f0;
            color: var(--app-error);
        }

        .auth-errors-list {
            margin: .5rem 0 0;
            padding-left: 1.2rem;
            font-weight: 500;
        }

        .auth-btn {
            border: 0;
            border-radius: .92rem;
            padding: .7rem 1rem;
            font-size: .84rem;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #fff;
            background: linear-gradient(90deg, var(--app-accent), var(--app-accent-2));
            box-shadow: 0 10px 20px rgba(200, 78, 9, .23);
            transition: transform .18s ease, box-shadow .18s ease;
        }

        .auth-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 13px 24px rgba(163, 63, 6, .3);
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

        @media (max-width: 991.98px) {
            .app-sidebar {
                display: none;
            }

            .app-content {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="app-shell">
        <aside class="app-sidebar d-none d-lg-flex flex-column justify-content-between">
            <div>
                <a href="{{ Auth::user()->hasRole('gestionnaire') ? route('admin.dashboard') : route('catalogue.index') }}" class="app-brand">
                    <x-application-logo class="rounded" style="width: 42px; height: 42px;" />
                    <div>
                        <div class="app-brand-name">{{ config('app.name', 'ISI Burger') }}</div>
                        <div class="app-brand-sub">Espace connecte</div>
                    </div>
                </a>

                <div class="app-menu-title">Navigation</div>
                <nav>
                    @role('gestionnaire')
                        <a href="{{ route('admin.dashboard') }}" class="app-menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
                        <a href="{{ route('admin.commandes.index') }}" class="app-menu-link {{ request()->routeIs('admin.commandes.*') ? 'active' : '' }}">Commandes</a>
                        <a href="{{ route('burgers.index') }}" class="app-menu-link {{ request()->routeIs('burgers.*') ? 'active' : '' }}">Burgers</a>
                        <a href="{{ route('categories.index') }}" class="app-menu-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">Categories</a>
                    @endrole

                    @role('client')
                        <a href="{{ route('catalogue.index') }}" class="app-menu-link {{ request()->routeIs('catalogue.*') ? 'active' : '' }}">Catalogue</a>
                        <a href="{{ route('commandes.mes') }}" class="app-menu-link {{ request()->routeIs('commandes.mes') ? 'active' : '' }}">Mes commandes</a>
                    @endrole
                </nav>
            </div>

            <div class="app-user">
                <div class="app-user-name">{{ Auth::user()->name }}</div>
                <div class="app-user-role">{{ Auth::user()->roles->pluck('name')->first() }}</div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn app-logout w-100">Se deconnecter</button>
                </form>
            </div>
        </aside>

        <div class="app-main">
            @include('layouts.navigation')

            <main class="app-content">
                @include('layouts.flash-messages')

                @if (isset($header))
                    <div class="app-header">
                        {{ $header }}
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    @stack('scripts')
    @yield('scripts')
</body>
</html>
