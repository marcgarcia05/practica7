<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    /**
     * Mostrar lista de artículos (con paginación y filtros)
     */
    public function index(Request $request)
    {
        $currentPage = $request->input('page', 1);
        $rpp = $request->input('rpp', 10); // Resultados por página
        $filter = $request->input('filter', 'data'); // Filtro (alphabetical o data)
        $order = $request->input('order', 'desc'); // Orden (asc o desc)
        $search = $request->input('search', ''); // Término de búsqueda
        $isQrMode = $request->has('qr');
        
        // Calcular offset para la paginación
        $offset = ($currentPage - 1) * $rpp;
        
        // Si hay usuario autenticado, mostrar solo sus artículos
        if (Auth::check() && !$isQrMode) {
            $userID = Auth::id();
            
            // Si hay búsqueda
            if (!empty($search)) {
                $articles = Article::searchArticlesUsuariPaginats($offset, $rpp, $userID, $filter, $order, $search);
                $total = Article::searchTotalArticlesUsuari($userID, $search);
            } else {
                $articles = Article::obtenirArticlesUsuariPaginats($offset, $rpp, $userID, $filter, $order);
                $total = Article::obtenirTotalArticlesUsuari($userID);
            }
        } else {
            // Mostrar todos los artículos (público)
            if (!empty($search)) {
                $articles = Article::searchArticlesPaginats($offset, $rpp, $filter, $order, $search);
                $total = Article::searchTotalArticles($search);
            } else {
                $articles = Article::obtenirArticlesPaginats($offset, $rpp, $filter, $order);
                $total = Article::obtenirTotalArticles();
            }
        }
        
        // Calcular número total de páginas
        $totalPages = ceil($total / $rpp);
        
        // Si la página solicitada es mayor que el total, volver a la primera página
        if ($currentPage > $totalPages && $totalPages > 0) {
            return redirect()->route('articles.index', [
                'page' => 1,
                'rpp' => $rpp,
                'filter' => $filter,
                'order' => $order,
                'search' => $search,
                'qr' => $isQrMode ? 1 : null
            ]);
        }
        
        // Pasar variables a la vista
        return view('articles.index', compact(
            'articles', 
            'currentPage', 
            'totalPages', 
            'rpp', 
            'filter', 
            'order',
            'isQrMode'
        ));
    }

    /**
     * Búsqueda Ajax para artículos
     */
    public function searchAjax(Request $request)
    {
        $page = $request->input('page', 1);
        $rpp = $request->input('rpp', 5);
        $filter = $request->input('filter', 'data');
        $order = $request->input('order', 'asc');
        $search = $request->input('search', '');
        
        $offset = ($page - 1) * $rpp;
        
        // Si hay usuario autenticado, mostrar solo sus artículos
        if (Auth::check()) {
            $userID = Auth::id();
            $articles = Article::searchArticlesUsuariPaginats($offset, $rpp, $userID, $filter, $order, $search);
        } else {
            $articles = Article::searchArticlesPaginats($offset, $rpp, $filter, $order, $search);
        }
        
        // Generar HTML para la respuesta AJAX
        $html = $this->generateArticlesHtml($articles);
        
        return response()->json(['html' => $html]);
    }

    /**
     * Generar HTML para mostrar artículos
     */
    private function generateArticlesHtml($articles)
    {
        $html = "<div class='container text-center position-flex'><div class='row row-cols-3 mx-auto'>";
        
        if (count($articles) > 0) {
            foreach ($articles as $article) {
                $html .= "<div class='col mt-3'><div class='card' style='width: 18rem;'><div class='card-body'>";
                $html .= "<h5 class='card-title'>{$article->Titol}</h5>";
                $html .= "<p class='card-text'>{$article->Cos}</p>";
                
                // Si el usuario está autenticado y es el dueño del artículo
                if (Auth::check() && Auth::id() == $article->User_ID) {
                    $editUrl = route('articles.edit', $article->ID);
                    
                    // Usar enlace <a> para editar (método GET)
                    $html .= "<a href='{$editUrl}' class='btn btn-warning'>";
                    $html .= "<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil-square' viewBox='0 0 16 16'>";
                    $html .= "<path d='M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z'/>";
                    $html .= "<path fill-rule='evenodd' d='M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z'/>";
                    $html .= "</svg></a>";
                    
                    // Usar formulario para eliminar (DELETE requiere un formulario)
                    $deleteUrl = route('articles.delete', $article->ID);
                    $html .= "<form action='{$deleteUrl}' method='post' style='display:inline;'>";
                    $html .= "<input type='hidden' name='_token' value='" . csrf_token() . "'>";
                    $html .= "<button type='submit' class='btn btn-danger mx-1'>";
                    $html .= "<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash-fill' viewBox='0 0 16 16'>";
                    $html .= "<path d='M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0'/>";
                    $html .= "</svg></button></form>";
                }
                
                $html .= "</div></div></div>";
            }
        } else {
            $html .= "<p>No tens cap producte disponible</p>";
        }
        
        $html .= "</div></div>";
        
        return $html;
    }

    /**
     * Muestra la vista para crear un artículo
     */
    public function create()
    {
        return view('articles.create');
    }
    
    /**
     * Muestra la vista para crear un artículo con QR
     */
    public function createQr()
    {
        // Obtener todos los artículos (con o sin filtrado según sea necesario)
        $articles = Article::obtenirArticlesPaginats(0, 100, 'data', 'desc');
        return view('articles.create_qr', compact('articles'));
    }

    /**
     * Muestra la vista para cargar un QR
     */
    public function uploadQr()
    {
        return view('articles.upload_qr');
    }
    
    /**
     * Procesa un QR cargado por el usuario
     */
    public function processQr(Request $request)
    {
        // Validar que se ha subido un archivo
        $request->validate([
            'qr_image' => 'required|image|mimes:png|max:2048',
        ]);

        if ($request->hasFile('qr_image') && $request->file('qr_image')->isValid()) {
            $tmpFilePath = $request->file('qr_image')->path();
            
            try {
                // Aquí iría la lógica para procesar el QR con la biblioteca Zxing
                // Como no tenemos la biblioteca instalada aún, simularemos el comportamiento
                
                // En un entorno real, usaríamos el código como:
                // $qrcode = new \Zxing\QrReader($tmpFilePath);
                // $text = $qrcode->text();
                
                // Para propósitos de demostración, generamos una URL simulada
                $text = route('articles.create') . '?demo=1';
                
                if (!empty($text)) {
                    // Redirigir al usuario al enlace del QR
                    return redirect($text);
                } else {
                    return redirect()->back()->with('error', 'No se pudo leer el código QR. Intenta con otra imagen.');
                }
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Error al procesar el QR: ' . $e->getMessage());
            }
        }
        
        return redirect()->back()->with('error', 'Error al subir la imagen.');
    }

    /**
     * Almacena un artículo recién creado
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'Titol' => 'required|string|max:255',
            'Cos' => 'required|string',
        ], [
            'Titol.required' => 'ERROR - TITOL NO POT ESTAR BUIT!!',
            'Cos.required' => 'ERROR - COS NO POT ESTAR BUIT!!',
        ]);
        
        // Sanear los datos para evitar code injection (como lo hacías en el original)
        $titol = htmlspecialchars($validated['Titol']);
        $cos = htmlspecialchars($validated['Cos']);
        $usuariID = Auth::id();
        
        $article = Article::inserirArticle(
            $titol,
            $cos,
            $usuariID
        );
        
        $successMessage = "<div class='alertes alert alert-success d-flex align-items-center' role='alert'>DADES INTRODUIDES CORRECTAMENT!!<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></div>";
        
        return redirect()->route('articles.create')->with('success', $successMessage);
    }

    /**
     * Mostrar el formulario para editar un artículo
     */
    public function edit($id)
    {
        $article = Article::consultarArticle($id);
        
        if (!$article) {
            return redirect()->route('articles.index')
                ->with('error', '<div class="alertes alert alert-danger d-flex align-items-center" role="alert">ERROR - AQUEST ID NO EXISTEIX!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></div>');
        }
        
        return view('articles.edit', compact('article'));
    }
    
    /**
     * Actualizar un artículo en la base de datos
     */
    public function update(Request $request, $id)
    {
        // Validar los datos enviados
        $validated = $request->validate([
            'titol' => 'required',
            'cos' => 'required',
        ], [
            'titol.required' => 'ERROR - TITOL NO POT ESTAR BUIT!!',
            'cos.required' => 'ERROR - COS NO POT ESTAR BUIT!!',
        ]);
        
        $id = htmlspecialchars($id);
        $titol = htmlspecialchars($request->titol);
        $cos = htmlspecialchars($request->cos);
        
        $article = Article::consultarArticle($id);
        
        if (!$article) {
            return redirect()->route('articles.index')
                ->with('error', '<div class="alertes alert alert-danger d-flex align-items-center" role="alert">ERROR - AQUEST ID NO EXISTEIX!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></div>');
        }
        
        // Actualizar el artículo
        Article::modificarArticle($id, $titol, $cos);
        
        return redirect()->route('articles.index')
            ->with('success', '<div class="alertes alert alert-success d-flex align-items-center" role="alert">DADES ACTUALITZADES CORRECTAMENT<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></div>');
    }

    /**
     * Elimina un artículo
     */
    public function delete($id)
    {
        $article = Article::consultarArticle($id);
        
        // Comprobar que el artículo existe y pertenece al usuario actual
        if (!$article || $article->User_ID != Auth::id()) {
            return redirect()->route('articles.index')->with('error', 'No tienes permiso para eliminar este artículo');
        }
        
        Article::eliminarArticle($id);
        
        return redirect()->route('articles.index')->with('message', 'Artículo eliminado correctamente');
    }

    /**
     * Genera un código QR para un artículo
     */
    public function generateQr($id)
    {
        $article = Article::consultarArticle($id);
        
        if (!$article) {
            return redirect()->route('articles.index')->with('error', 'Artículo no encontrado');
        }
        
        // Aquí iría la lógica de generación del QR
        // Por ahora, simplemente devolvemos una vista con el artículo
        return view('articles.qr', compact('article'));
    }
}
