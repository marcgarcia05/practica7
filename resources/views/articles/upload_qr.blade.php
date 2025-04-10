@extends('layouts.app')

@section('content')
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
    <form action="{{ route('articles.process.qr') }}" method="POST" enctype="multipart/form-data" class='form-inline justify-content-arround'>
        @csrf
        <div class="mb-3">
            <label for="file">Selecciona un arxiu:</label>
            <input type="file" name="qr_image" accept="image/png" required>
            <br><br>
            <input type="submit" class="btn btn-primary" value="Carregar Article">
        </div>
        
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
    </form>
</div>
@endsection
