<nav class="navbar navbar-expand-lg app-topbar">
    <div class="container-fluid px-3 px-md-4">
        <a class="navbar-brand d-lg-none fw-bold" href="{{ auth()->check() && Auth::user()->hasRole('gestionnaire') ? route('admin.dashboard') : route('catalogue.index') }}">
            {{ config('app.name', 'ISI Burger') }}
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNavbar" aria-controls="topNavbar" aria-expanded="false" aria-label="Afficher le menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="topNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 d-lg-none">
                @guest
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('catalogue.*') ? 'active fw-semibold text-dark' : '' }}" href="{{ route('catalogue.index') }}">Catalogue</a></li>
                @endguest

                @role('gestionnaire')
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active fw-semibold text-dark' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.commandes.*') ? 'active fw-semibold text-dark' : '' }}" href="{{ route('admin.commandes.index') }}">Commandes</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('burgers.*') ? 'active fw-semibold text-dark' : '' }}" href="{{ route('burgers.index') }}">Burgers</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('categories.*') ? 'active fw-semibold text-dark' : '' }}" href="{{ route('categories.index') }}">Categories</a></li>
                @endrole

                @role('client')
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('catalogue.*') ? 'active fw-semibold text-dark' : '' }}" href="{{ route('catalogue.index') }}">Catalogue</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('commandes.mes') ? 'active fw-semibold text-dark' : '' }}" href="{{ route('commandes.mes') }}">Mes commandes</a></li>
                @endrole
            </ul>

            <ul class="navbar-nav ms-auto align-items-lg-center gap-2">
                @auth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('profile.*') ? 'fw-semibold text-dark' : '' }}" href="{{ route('profile.edit') }}">Profil</a>
                    </li>

                    <li class="nav-item dropdown">
                        <button class="btn btn-light border dropdown-toggle rounded-pill px-3" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Mon profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Se deconnecter</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endauth

                @guest
                    <li class="nav-item">
                        <a class="btn btn-outline-dark rounded-pill px-3" href="{{ route('login') }}">Se connecter</a>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
