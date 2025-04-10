<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/alertes.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    @include('layouts.navbar')
    
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Editar Usuario</h1>
            <a href="{{ route('admin.users') }}" class="btn btn-primary">← Volver a Usuarios</a>
        </div>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.update-user', $user->ID) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="Nom_usuari" class="form-label">Nombre de usuario</label>
                        <input type="text" class="form-control" id="Nom_usuari" name="Nom_usuari" value="{{ old('Nom_usuari', $user->Nom_usuari) }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="Email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="Email" name="Email" value="{{ old('Email', $user->Email) }}" required>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="Admin" name="Admin" value="1" {{ $user->Admin == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="Admin">¿Es administrador?</label>
                    </div>
                    
                    <div class="mb-3">
                        <p class="mb-1"><strong>Tipo de cuenta:</strong></p>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="accountTypeEmail" {{ !$user->Google && !$user->Github ? 'checked' : '' }} disabled>
                            <label class="form-check-label" for="accountTypeEmail">Email</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="accountTypeGoogle" {{ $user->Google ? 'checked' : '' }} disabled>
                            <label class="form-check-label" for="accountTypeGoogle">Google</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="accountTypeGithub" {{ $user->Github ? 'checked' : '' }} disabled>
                            <label class="form-check-label" for="accountTypeGithub">Github</label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <p class="mb-1"><strong>Fecha de registro:</strong> {{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : 'N/A' }}</p>
                        <p class="mb-1"><strong>Última actualización:</strong> {{ $user->updated_at ? $user->updated_at->format('d/m/Y H:i') : 'N/A' }}</p>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success">Actualizar Usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>