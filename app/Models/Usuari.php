<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class Usuari extends Authenticatable
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'usuaris';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'ID';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'Nom_usuari',
        'Contrasenya',
        'Email',
        'Token',
        'Google',
        'Github',
        'Admin'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'Contrasenya',
        'remember_token',
    ];

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->Contrasenya;
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'ID';
    }

    /**
     * Relación con los artículos del usuario
     */
    public function articles()
    {
        return $this->hasMany(Article::class, 'User_ID', 'ID');
    }

    /**
     * Inserir un nuevo usuario
     */
    public static function inserirUsuari($nom, $email, $password) {
        return self::create([
            'Nom_usuari' => $nom, 
            'Contrasenya' => bcrypt($password), 
            'Email' => $email
        ]);
    }

    /**
     * Inserir un usuario con Google
     */
    public static function inserirUsuariGoogle($nom, $email) {
        return self::create([
            'Nom_usuari' => $nom, 
            'Email' => $email,
            'Google' => true
        ]);
    }

    /**
     * Inserir un usuario con Github
     */
    public static function inserirUsuariGithub($nom, $email) {
        return self::create([
            'Nom_usuari' => $nom, 
            'Email' => $email, 
            'Github' => true
        ]);
    }

    /**
     * Obtener todos los usuarios
     */
    public static function getUsuaris() {
        return self::all();
    }

    /**
     * Comprobar si un email ya existe
     */
    public static function comprovarUsuari($email) {
        return self::where('Email', $email)->count();
    }

    /**
     * Obtener usuario por email
     */
    public static function getUsuari($email) {
        return self::where('Email', $email)->first();
    }

    /**
     * Obtener usuario por ID
     */
    public static function getUsuariByID($id) {
        return self::find($id);
    }

    /**
     * Obtener contraseña de usuario por ID
     */
    public static function getPasswd($id) {
        $user = self::find($id);
        return $user ? ['Contrasenya' => $user->Contrasenya] : null;
    }

    /**
     * Obtener usuario por token
     */
    public static function getUserByToken($token) {
        return self::where('Token', $token)->first();
    }

    /**
     * Actualizar contraseña de usuario
     */
    public static function updatePasswd($id, $passwd) {
        $user = self::find($id);
        if ($user) {
            $user->Contrasenya = bcrypt($passwd);
            return $user->save();
        }
        return false;
    }

    /**
     * Actualizar contraseña por token
     */
    public static function updatePasswdByToken($token, $newpasswd) {
        $user = self::where('Token', $token)->first();
        if ($user) {
            $user->Contrasenya = bcrypt($newpasswd);
            return $user->save();
        }
        return false;
    }

    /**
     * Actualizar perfil de usuario
     */
    public static function updateProfile($id, $nom) {
        $user = self::find($id);
        if ($user) {
            $user->Nom_usuari = $nom;
            return $user->save();
        }
        return false;
    }

    /**
     * Inserir token de recuperación
     */
    public static function inserirToken($email, $token) {
        return self::where('Email', $email)->update(['Token' => $token]);
    }

    /**
     * Eliminar token
     */
    public static function deleteToken($token) {
        return self::where('Token', $token)->update(['Token' => null]);
    }

    /**
     * Eliminar usuario
     */
    public static function deleteUsuari($id) {
        return self::destroy($id);
    }
}
