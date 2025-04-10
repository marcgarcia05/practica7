<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Código QR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/alertes.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    @include('layouts.navbar')
    
    <div class="container mt-4">
        <h1 class="text-center">Código QR para el artículo</h1>
        
        <div class="row justify-content-center mt-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5>{{ $article->Titol }}</h5>
                    </div>
                    <div class="card-body">
                        <p>{{ $article->Cos }}</p>
                        
                        <div class="text-center mt-4">                            <!-- En un proyecto real, aquí se generaría el código QR con una librería -->
                            <!-- Simulación de código QR con un placeholder -->
                            <div class="border p-4 d-inline-block">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?data={{ urlencode(route('articles.index') . '?id=' . $article->ID) }}&size=200x200" alt="QR Code" id="qrImage" />
                            </div>
                            
                            <p class="mt-3">Escanea este código QR para acceder al artículo</p>
                              <!-- Botón para descargar el QR -->
                            <button onclick="downloadQR()" class="btn btn-success mt-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                                    <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                                    <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                                </svg>
                                Descargar QR
                            </button>
                        </div>
                    </div>
                </div>
                  <div class="text-center mt-3">
                    <a href="{{ route('articles.index') }}" class="btn btn-primary">Volver</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Función para descargar la imagen del QR
        function downloadQR() {
            // URL de la imagen QR
            const qrUrl = document.getElementById('qrImage').src;
            
            // Crear un canvas temporal
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            
            // Crear una nueva imagen
            const img = new Image();
            img.crossOrigin = 'Anonymous'; // Importante para permitir descargar imágenes de otros dominios
            
            img.onload = function() {
                // Configurar el tamaño del canvas
                canvas.width = img.width;
                canvas.height = img.height;
                
                // Dibujar la imagen en el canvas
                ctx.drawImage(img, 0, 0);
                
                // Crear un enlace de descarga
                const a = document.createElement('a');
                a.download = 'qr_articulo_{{ $article->ID }}.png';
                
                // Convertir el canvas a una URL de datos
                a.href = canvas.toDataURL('image/png');
                
                // Simular un clic para iniciar la descarga
                document.body.appendChild(a);
                a.click();
                
                // Limpiar
                document.body.removeChild(a);
            };
            
            // Establecer la fuente de la imagen
            img.src = qrUrl;
        }
    </script>
</body>
</html>