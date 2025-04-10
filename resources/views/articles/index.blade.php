<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Artículos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/alertes.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById("search");
        const resultsContainer = document.getElementById("results");
        const paginationContainer = document.getElementById("pagination");

        if (!searchInput) {
            console.error("El campo de búsqueda no se encontró.");
            return;
        }

        function buscar(pagina = 1) {
            const search = searchInput.value.trim();
            console.log("Búsqueda:", search);
            const rpp = 5;
            const filter = "data";
            const order = "asc";

            fetch("{{ route('articles.search.ajax') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: new URLSearchParams({
                        search,
                        page: pagina,
                        rpp,
                        filter,
                        order
                    })
                })
                .then(response => response.text())
                .then(data => {
                    console.log("Respuesta del servidor (sin parsear):", data);
                    try {
                        let jsonData = JSON.parse(data);
                        resultsContainer.innerHTML = jsonData.html;
                    } catch (error) {
                        console.error("Error al parsear JSON:", error);
                        resultsContainer.innerHTML = `<p>Error al procesar los resultados. Revisa la consola.</p>`;
                    }
                })
                .catch(error => console.error("Error en la búsqueda:", error));
        }

        function actualizarPaginacion(totalPaginas, paginaActual) {
            let paginacionHTML = "";
            for (let i = 1; i <= totalPaginas; i++) {
                paginacionHTML += `<button class="page-btn ${i === paginaActual ? 'active' : ''}" data-page="${i}">${i}</button>`;
            }
            paginationContainer.innerHTML = paginacionHTML;

            document.querySelectorAll(".page-btn").forEach(btn => {
                btn.addEventListener("click", function() {
                    buscar(this.dataset.page);
                });
            });
        }

        if (searchInput) {
            searchInput.addEventListener("input", () => buscar());
        }
    });
</script>

<body>    @include('layouts.navbar')

    @auth
        <div>            <a href="{{ route('articles.create') }}" class="btn btn-outline-success btn-lg mt-2 mx-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
                    <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                </svg>
            </a>            <a href="{{ route('articles.upload.qr') }}" class="btn btn-outline-success btn-lg mt-2 mx-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-qr-code" viewBox="0 0 16 16">
                    <path d="M2 2h2v2H2z"/><path d="M6 0v6H0V0zM5 1H1v4h4zM4 12H2v2h2z"/><path d="M6 10v6H0v-6zm-5 1v4h4v-4zm11-9h2v2h-2z"/>
                    <path d="M10 0v6h6V0zm5 1v4h-4V1zM8 1V0h1v2H8v2H7V1zm0 5V4h1v2zM6 8V7h1V6h1v2h1V7h5v1h-4v1H7V8zm0 0v1H2V8H1v1H0V7h3v1zm10 1h-1V7h1zm-1 0h-1v2h2v-1h-1zm-4 0h2v1h-1v1h-1zm2 3v-1h-1v1h-1v1H9v1h3v-2zm0 0h3v1h-2v1h-1zm-4-1v1h1v-2H7v1z"/>
                    <path d="M7 12h1v3h4v1H7zm9 2v2h-3v-1h2v-1z"/>
                </svg>
            </a>
        </div>
        <br>
        <h1 class="display-3 text-center">Els teus articles</h1>
    @else
        <br>
        <h1 class="display-3 text-center">Articles</h1>
    @endauth

    <div id="results" class="mt-3 text-center">
        @if(isset($articles))
            <div class="container text-center position-flex">
                <div class="row row-cols-3 mx-auto">
                    @forelse($articles as $article)
                        <div class="col mt-3">
                            <div class="card" style="width: 18rem;">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $article->Titol }}</h5>
                                    <p class="card-text">{{ $article->Cos }}</p>
                                    
                                    @if(isset($isQrMode) && $isQrMode)
                                        <!-- Botón para generar QR en modo QR -->
                                        <a href="{{ route('articles.qr', $article->ID) }}" class="btn btn-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-qr-code" viewBox="0 0 16 16">
                                                <path d="M2 2h2v2H2z"/>
                                                <path d="M6 0v6H0V0zM5 1H1v4h4zM4 12H2v2h2z"/>
                                                <path d="M6 10v6H0v-6zm-5 1v4h4v-4zm11-9h2v2h-2z"/>
                                                <path d="M10 0v6h6V0zm5 1v4h-4V1zM8 1V0h1v2H8v2H7V1z"/>
                                                <path d="M10 5.5v1h1v1h-1v-1h-1v-1zM11 6.5v1h1v-1z"/>
                                            </svg>
                                            Generar QR
                                        </a>
                                    @else
                                        @auth
                                            @if(auth()->user()->ID == $article->User_ID)
                                                <!-- Enlace para editar (método GET) -->
                                                <a href="{{ route('articles.edit', $article->ID) }}" class="btn btn-warning">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                                    </svg>
                                                </a>
                                                <!-- Formulario para eliminar (requiere método POST) -->
                                                <form action="{{ route('articles.delete', $article->ID) }}" method="post" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger mx-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                                            <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        @endauth
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <p>No tens cap producte disponible</p>
                    @endforelse
                </div>
            </div>
        @endif
        
        @if(session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
        
        @if(isset($logout))
            <div class="alert alert-info">
                {{ $logout }}
            </div>
        @endif
    </div>

    <div id="pagination" class="mt-3 flex-bottom">
        <nav>
            <!-- Dropdown para resultados por página -->
            @isset($rpp)
                <div class="dropdown d-flex mx-3">
                    <p>Articles per pagina</p>
                    <button class="btn btn-primary dropdown-toggle mx-2" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <span id="selectedOption">{{ $rpp }}</span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" href="{{ route('articles.index', ['page' => 1, 'rpp' => 5, 'filter' => $filter, 'order' => $order]) }}">5</a></li>
                        <li><a class="dropdown-item" href="{{ route('articles.index', ['page' => 1, 'rpp' => 10, 'filter' => $filter, 'order' => $order]) }}">10</a></li>
                        <li><a class="dropdown-item" href="{{ route('articles.index', ['page' => 1, 'rpp' => 15, 'filter' => $filter, 'order' => $order]) }}">15</a></li>
                    </ul>
                </div>
            @endisset

            <!-- Dropdown para ordenación -->
            @isset($filter, $order)
                <div class="dropdown d-flex mx-3 mt-1">
                    <p>Order By</p>
                    <button class="btn btn-primary dropdown-toggle mx-2" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <span id="selectedOption">{{ ucfirst($filter) }} ({{ strtoupper($order) }})</span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" href="{{ route('articles.index', ['page' => 1, 'rpp' => $rpp, 'filter' => 'data', 'order' => 'asc']) }}">Data (ASC)</a></li>
                        <li><a class="dropdown-item" href="{{ route('articles.index', ['page' => 1, 'rpp' => $rpp, 'filter' => 'data', 'order' => 'desc']) }}">Data (DESC)</a></li>
                        <li><a class="dropdown-item" href="{{ route('articles.index', ['page' => 1, 'rpp' => $rpp, 'filter' => 'alphabetical', 'order' => 'asc']) }}">Alphabetical (ASC)</a></li>
                        <li><a class="dropdown-item" href="{{ route('articles.index', ['page' => 1, 'rpp' => $rpp, 'filter' => 'alphabetical', 'order' => 'desc']) }}">Alphabetical (DESC)</a></li>
                    </ul>
                </div>
            @endisset

            <!-- Paginación -->
            <ul class="pagination justify-content-center">
                @if(isset($currentPage, $totalPages))
                    <!-- Botón anterior -->
                    <li class="page-item {{ $currentPage <= 1 ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $currentPage > 1 ? route('articles.index', ['page' => $currentPage - 1, 'rpp' => $rpp, 'filter' => $filter, 'order' => $order]) : '#' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                                <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                            </svg>
                        </a>
                    </li>

                    <!-- Números de página -->
                    @for($i = 1; $i <= $totalPages; $i++)
                        <li class="page-item {{ $i == $currentPage ? 'active' : '' }}" aria-current="page">
                            <a class="page-link" href="{{ route('articles.index', ['page' => $i, 'rpp' => $rpp, 'filter' => $filter, 'order' => $order]) }}">{{ $i }}</a>
                        </li>
                    @endfor

                    <!-- Botón siguiente -->
                    <li class="page-item {{ $currentPage >= $totalPages ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $currentPage < $totalPages ? route('articles.index', ['page' => $currentPage + 1, 'rpp' => $rpp, 'filter' => $filter, 'order' => $order]) : '#' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-right-fill" viewBox="0 0 16 16">
                                <path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"/>
                            </svg>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</body>
</html>