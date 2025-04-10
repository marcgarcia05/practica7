<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuari;

class UsuariController extends Controller
{
    /**
     * Mostrar lista de usuarios
     */
    public function index()
    {
        $usuarios = Usuari::getUsuaris();
        return response()->json($usuarios);
    }

    /**
     * Mostrar usuario por ID
     */
    public function show($id)
    {
        $usuario = Usuari::getUsuariByID($id);
        return response()->json($usuario);
    }

    /**
     * Crear un nuevo usuario
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'Nom_usuari' => 'required|string|max:255',
            'Email' => 'required|email|unique:usuaris,Email',
            'Contrasenya' => 'required|string|min:6',
        ]);

        $usuario = Usuari::inserirUsuari(
            $validated['Nom_usuari'],
            $validated['Email'],
            $validated['Contrasenya']
        );

        return response()->json($usuario, 201);
    }

    /**
     * Actualizar un usuario existente
     */
    public function update(Request $request, $id)
    {
        $usuario = Usuari::getUsuariByID($id);
        
        if (!$usuario) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }
        
        if ($request->has('Nom_usuari')) {
            Usuari::updateProfile($id, $request->Nom_usuari);
        }
        
        if ($request->has('Contrasenya')) {
            Usuari::updatePasswd($id, $request->Contrasenya);
        }
        
        return response()->json(Usuari::getUsuariByID($id));
    }

    /**
     * Eliminar un usuario
     */
    public function destroy($id)
    {
        $deleted = Usuari::deleteUsuari($id);
        
        if ($deleted) {
            return response()->json(['message' => 'Usuario eliminado correctamente']);
        }
        
        return response()->json(['message' => 'Usuario no encontrado'], 404);
    }
}
