<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Article extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'articles';

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
        'Titol',
        'Cos',
        'User_ID'
    ];

    /**
     * Relación con el usuario autor
     */
    public function usuari()
    {
        return $this->belongsTo(Usuari::class, 'User_ID', 'ID');
    }

    /**
     * Inserir un nuevo artículo
     */
    public static function inserirArticle($titol, $cos, $userID)
    {
        return self::create([
            'Titol' => $titol,
            'Cos' => $cos,
            'User_ID' => $userID
        ]);
    }

    /**
     * Modificar un artículo existente
     */
    public static function modificarArticle($id, $titol, $cos)
    {
        $article = self::find($id);
        if ($article) {
            $article->Titol = $titol;
            $article->Cos = $cos;
            return $article->save();
        }
        return false;
    }

    /**
     * Modificar el ID de usuario en artículos
     */
    public static function modificarIdArticle($id, $userID)
    {
        return self::where('User_ID', $id)->update(['User_ID' => $userID]);
    }

    /**
     * Eliminar un artículo por ID
     */
    public static function eliminarArticle($id)
    {
        return self::destroy($id);
    }

    /**
     * Consultar un artículo por ID
     */
    public static function consultarArticle($id)
    {
        return self::find($id);
    }

    /**
     * Obtener artículos paginados con filtros
     */
    public static function obtenirArticlesPaginats($offset, $rpp, $filtre, $ordre)
    {
        $query = self::query();
        
        if ($filtre == 'alphabetical') {
            $query->orderBy('Titol', $ordre == 'desc' ? 'desc' : 'asc');
        } else if ($filtre == 'data') {
            $query->orderBy('ID', $ordre == 'desc' ? 'desc' : 'asc');
        } else {
            $query->orderBy('ID', 'desc');
        }
        
        return $query->offset($offset)->limit($rpp)->get();
    }

    /**
     * Buscar y paginar artículos
     */
    public static function searchArticlesPaginats($offset, $rpp, $filtre, $ordre, $search)
    {
        $query = self::where('Titol', 'like', "%$search%");
        
        $columnaOrden = ($filtre == 'data') ? 'ID' : 'Titol';
        $ordenSQL = ($ordre == 'desc') ? 'desc' : 'asc';
        
        $query->orderBy($columnaOrden, $ordenSQL);
        
        return $query->offset($offset)->limit($rpp)->get();
    }

    /**
     * Obtener artículos de un usuario específico (paginados con filtros)
     */
    public static function obtenirArticlesUsuariPaginats($offset, $rpp, $usuariID, $filtre, $ordre)
    {
        $query = self::where('User_ID', $usuariID);
        
        if ($filtre == 'alphabetical') {
            $query->orderBy('Titol', $ordre == 'desc' ? 'desc' : 'asc');
        } else if ($filtre == 'data') {
            $query->orderBy('ID', $ordre == 'desc' ? 'desc' : 'asc');
        } else {
            $query->orderBy('ID', 'desc');
        }
        
        return $query->offset($offset)->limit($rpp)->get();
    }

    /**
     * Buscar y paginar artículos de un usuario específico
     */
    public static function searchArticlesUsuariPaginats($offset, $rpp, $usuariID, $filtre, $ordre, $search)
    {
        $query = self::where('User_ID', $usuariID)
                     ->where('Titol', 'like', "%$search%");
        
        if ($filtre == 'alphabetical') {
            $query->orderBy('Titol', $ordre == 'desc' ? 'desc' : 'asc');
        } else if ($filtre == 'data') {
            $query->orderBy('ID', $ordre == 'desc' ? 'desc' : 'asc');
        } else {
            $query->orderBy('Titol', 'desc');
        }
        
        return $query->offset($offset)->limit($rpp)->get();
    }

    /**
     * Obtener el total de artículos
     */
    public static function obtenirTotalArticles()
    {
        return self::count();
    }

    /**
     * Obtener el total de artículos que coinciden con la búsqueda
     */
    public static function searchTotalArticles($search)
    {
        return self::where('Titol', 'like', "%$search%")->count();
    }

    /**
     * Obtener el total de artículos de un usuario
     */
    public static function obtenirTotalArticlesUsuari($userID)
    {
        return self::where('User_ID', $userID)->count();
    }

    /**
     * Obtener el total de artículos de un usuario que coinciden con la búsqueda
     */
    public static function searchTotalArticlesUsuari($userID, $search)
    {
        return self::where('User_ID', $userID)
                   ->where('Titol', 'like', "%$search%")
                   ->count();
    }

    /**
     * Obtener artículo por título
     */
    public static function getArticle($nom)
    {
        return self::where('Titol', 'like', "%$nom%")->first();
    }
}
