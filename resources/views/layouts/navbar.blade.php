<nav class="navbar navbar-dark bg-primary">
    <div class="container-fluid">
        @auth
            <a class="navbar-brand mx-2" href="{{ route('articles.index') }}">Benvingut/da</a>
        @else
            <a class="navbar-brand mx-2" href="{{ route('articles.index') }}">Pràctica 07</a>
        @endauth

        <!-- Campo de búsqueda -->
        <div class="row mx-auto">
            <div class="col">
                <input class="form-control mr-sm-1" type="search" placeholder="Search" id="search" name="search" aria-label="Search">
            </div>
        </div>

        <!-- Opciones de usuario/login -->
        @guest
            <form action="#" method="post" class="form-inline justify-content-arround">
                @csrf
                <a href="{{ route('login') }}" class="btn btn-outline-light mx-2">Login</a>
                <a href="{{ route('register') }}" class="btn btn-outline-light mx-2">Registrar-se</a>
            </form>
        @else
            <div class="dropdown">
                <button class="navbar-brand mx-6 nav-link dropdown-toggle list-unstyled" href="#" id="navbarDropdown" 
                       role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ Auth::user()->Nom_usuari }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">                    <!-- Opciones de administrador -->
                    @if(Auth::user()->admin == 1)
                        <li><a class="dropdown-item" href="{{ route('user.admin') }}">Admin</a></li>
                    @endif

                    <li><a class="dropdown-item" href="{{ route('profile') }}">Editar Perfil</a></li>
                    <li><a class="dropdown-item" href="{{ route('articles.index') }}?qr=1">Veure tots els articles</a></li>
                    
                    <li><hr class="dropdown-divider"></li>
                    
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">Logout</button>
                        </form>
                    </li>
                </ul>            </div>
        @endguest
    </div>
</nav>