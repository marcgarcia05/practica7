<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuari;
use Illuminate\Support\Facades\Auth;

use Illuminate\Routing\Controller;

class UserAdminController extends Controller
{
    /**
     * Constructor que aplica el middleware de autenticación
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar lista de usuarios para administración
     */
    public function index()
    {
        // Verificar manualmente si el usuario es administrador
        if (!Auth::check() || Auth::user()->admin != 1) {
            return redirect()->route('articles.index')
                ->with('error', 'No tienes permisos de administrador.');
        }

        $users = Usuari::all();
        return view('admin.users', compact('users'));
    }

    /**
     * Eliminar un usuario
     */
    public function deleteUser($id)
    {
        // Verificar manualmente si el usuario es administrador
        if (!Auth::check() || Auth::user()->admin != 1) {
            return redirect()->route('articles.index')
                ->with('error', 'No tienes permisos de administrador.');
        }

        $user = Usuari::find($id);
        
        // No permitir eliminar administradores
        if ($user && $user->admin == 1) {
            return redirect()->route('user.admin')
                ->with('error', 'No se pueden eliminar usuarios administradores');
        }
        
        // Eliminar usuario
        if ($user) {
            $user->delete();
            return redirect()->route('user.admin')
                ->with('success', 'Usuario eliminado correctamente');
        }
        
        return redirect()->route('user.admin')
            ->with('error', 'Usuario no encontrado');
    }
}
