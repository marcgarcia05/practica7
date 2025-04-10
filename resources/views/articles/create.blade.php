<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Afegir Article</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/alertes.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    @include('layouts.navbar')
    
    <div class="position-relative">
        <div class="position-absolute top-0 start-0 mx-3 mt-3">
            <a href="{{ route('articles.index') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                    <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z" />
                </svg>
            </a>
        </div>
    </div>
    
    <div class="container mt-5">
        <h1>Afegir article</h1>
        <br>
        
        @if ($errors->any())
            <div class="alertes">
                @foreach ($errors->all() as $error)
                    <div class="alerta z-3 text-end alert alert-danger" role="alert">
                        {{ $error }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endforeach
            </div>
        @endif
        
        @if (session('success'))
            {!! session('success') !!}
        @endif
        
        <form action="{{ route('articles.store') }}" method="POST" class="form-inline justify-content-arround">
            @csrf
            <div class="mb-3">
                <label for="Titol" class="form-label">Títol</label>
                <input type="text" class="form-control" id="Titol" name="Titol" value="{{ old('Titol') }}">
            </div>

            <div class="mb-3">
                <label for="Cos" class="form-label">Cos</label>
                <textarea class="form-control" id="Cos" rows="4" name="Cos">{{ old('Cos') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
    </div>
</body>
</html>