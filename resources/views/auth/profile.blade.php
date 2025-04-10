<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Mi Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/alertes.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    @include('layouts.navbar')
    
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Mi Perfil</div>

                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="Nom_usuari" class="form-label">Nombre de usuario</label>
                                <input id="Nom_usuari" type="text" class="form-control @error('Nom_usuari') is-invalid @enderror" name="Nom_usuari" value="{{ old('Nom_usuari', Auth::user()->Nom_usuari) }}" required autocomplete="Nom_usuari" autofocus>
                                @error('Nom_usuari')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="Email" class="form-label">Email</label>
                                <input id="Email" type="email" class="form-control" value="{{ Auth::user()->Email }}" readonly disabled>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    Actualizar Perfil
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">Cambiar Contraseña</div>

                    <div class="card-body">
                        @if(session('password-success'))
                            <div class="alert alert-success">
                                {{ session('password-success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('profile.password') }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="current_password" class="form-label">Contraseña Actual</label>
                                <input id="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" required>
                                @error('current_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="Contrasenya" class="form-label">Nueva Contraseña</label>
                                <input id="Contrasenya" type="password" class="form-control @error('Contrasenya') is-invalid @enderror" name="Contrasenya" required>
                                @error('Contrasenya')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="Contrasenya_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                                <input id="Contrasenya_confirmation" type="password" class="form-control" name="Contrasenya_confirmation" required>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    Cambiar Contraseña
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>