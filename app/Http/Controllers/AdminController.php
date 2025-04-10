<?php

namespace App\Http\Controllers;

use App\Models\Usuari;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Routing\Controller;

class AdminController extends Controller
{
    /**
     * Constructor para aplicar middleware de autenticación
     */
    public function __construct()
    {
        $this->middleware('auth');
        // El middleware admin lo aplicaremos en las rutas
    }

    /**
     * Mostrar dashboard de administrador
     */
    public function dashboard()
    {
        // Obtener estadísticas básicas para el dashboard
        $stats = [
            'total_users' => Usuari::count(),
            'total_articles' => Article::count(),
            'google_users' => Usuari::where('Google', 1)->count(),
            'github_users' => Usuari::where('Github', 1)->count(),
        ];
        
        // Obtener lista de usuarios para administrar
        $users = Usuari::paginate(10);
        
        return view('admin.dashboard', compact('stats', 'users'));
    }
    
    /**
     * Mostrar lista de usuarios
     */
    public function users()
    {
        $users = Usuari::all();
        return view('admin.users', compact('users'));
    }
    
    // Removed duplicate deleteUser method to avoid redeclaration error.
    
    /**
     * Mostrar lista de artículos
     */
    public function articles()
    {
        $articles = Article::with('usuari')->paginate(15);
        return view('admin.articles', compact('articles'));
    }
    
    /**
     * Mostrar formulario para editar usuario
     */
    public function editUser($id)
    {
        $user = Usuari::findOrFail($id);
        return view('admin.edit_user', compact('user'));
    }
    
    /**
     * Actualizar usuario
     */
    public function updateUser(Request $request, $id)
    {
        $user = Usuari::findOrFail($id);
        
        $validated = $request->validate([
            'Nom_usuari' => 'required|string|max:255',
            'Email' => 'required|email|unique:usuaris,Email,' . $id . ',ID',
            'Admin' => 'boolean',
        ]);
        
        $user->Nom_usuari = $validated['Nom_usuari'];
        $user->Email = $validated['Email'];
        $user->Admin = $request->has('Admin') ? 1 : 0;
        $user->save();
        
        return redirect()->route('admin.users')->with('success', 'Usuario actualizado correctamente.');
    }
    
    /**
     * Eliminar usuario
     */
    public function deleteUser($id)
    {
        $user = Usuari::find($id);
        
        // No permitir eliminar administradores
        if ($user && $user->admin == 1) {
            return redirect()->route('admin.users')
                ->with('error', 'No se pueden eliminar usuarios administradores');
        }
        
        // Eliminar usuario
        if ($user) {
            $user->delete();
            return redirect()->route('admin.users')
                ->with('success', 'Usuario eliminado correctamente');
        }
        
        return redirect()->route('admin.users')
            ->with('error', 'Usuario no encontrado');
    }
}
