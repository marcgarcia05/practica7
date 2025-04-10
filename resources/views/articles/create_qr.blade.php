<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Seleccionar artículo para QR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/alertes.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    @include('layouts.navbar')
    
    <div class="container mt-4">
        <h1 class="text-center">Seleccionar artículo para generar QR</h1>
        
        @if(isset($articles) && count($articles) > 0)
            <div class="container text-center position-flex mt-4">
                <div class="row row-cols-3 mx-auto">
                    @foreach($articles as $article)
                        <div class="col mt-3">
                            <div class="card" style="width: 18rem;">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $article->Titol }}</h5>
                                    <p class="card-text">{{ $article->Cos }}</p>
                                    <form action="{{ route('articles.qr', $article->ID) }}" method="get">
                                        @csrf
                                        <button type="submit" class="btn btn-success">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-qr-code-scan" viewBox="0 0 16 16">
                                                <path d="M0 .5A.5.5 0 0 1 .5 0h3a.5.5 0 0 1 0 1H1v2.5a.5.5 0 0 1-1 0zm12 0a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0V1h-2.5a.5.5 0 0 1-.5-.5M.5 12a.5.5 0 0 1 .5.5V15h2.5a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5v-3a.5.5 0 0 1 .5-.5m15 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1 0-1H15v-2.5a.5.5 0 0 1 .5-.5M4 4h1v1H4z"/>
                                                <path d="M7 2H2v5h5zM3 3h3v3H3zm2 8H4v1h1z"/>
                                                <path d="M7 9H2v5h5zm-4 1h3v3H3zm8-6h1v1h-1z"/>
                                                <path d="M9 2h5v5H9zm1 1v3h3V3zM8 8v2h1v1H8v1h2v-2h1v2h1v-1h2v-1h-3V8zm2 2H9V9h1zm4 2h-1v1h-2v1h3zm-4 2v-1H8v1z"/>
                                                <path d="M12 9h2V8h-2z"/>
                                            </svg>
                                            Generar QR
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="alert alert-info mt-4 text-center">
                No hay artículos disponibles para generar códigos QR. <a href="{{ route('articles.create') }}" class="alert-link">Crear un artículo</a>.
            </div>
        @endif
        
        <div class="d-flex justify-content-center mt-4">
            <a href="{{ route('articles.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</body>
</html>