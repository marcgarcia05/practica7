<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UsuariController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\UserAdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Add authentication routes
Auth::routes();

// Ruta principal: Redirige a la lista de artículos
Route::get('/', function () {
    return redirect()->route('articles.index');
});

// Route named 'home' that also points to articles index
Route::get('/home', [ArticleController::class, 'index'])->name('home');

// Rutas para la autenticación
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    // Registro
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    // Password Reset Routes
    Route::get('forgot-password', [PasswordController::class, 'showForgotForm'])->name('password.request');
    Route::post('forgot-password', [PasswordController::class, 'sendResetLink'])->name('password.email');
    Route::get('reset-password/{token}', [PasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [PasswordController::class, 'resetPassword'])->name('password.update');
    
    // Social Login Routes
    Route::get('login/github', [AuthController::class, 'redirectToGithub'])->name('login.github');
    Route::get('login/github/callback', [AuthController::class, 'handleGithubCallback']);
    
    Route::get('login/google', [AuthController::class, 'redirectToGoogle'])->name('login.google');
    Route::get('login/google/callback', [AuthController::class, 'handleGoogleCallback']);
});

// Rutas que requieren autenticación
Route::middleware('auth')->group(function () {
    // Cerrar sesión
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Perfil de usuario
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('/profile', [AuthController::class, 'updateProfile']);
    Route::post('/profile/password', [AuthController::class, 'updatePassword'])->name('profile.password');
    
    // Administración de usuarios - Enfoque directo sin middleware 'admin'
    Route::get('/admin/users', [UserAdminController::class, 'index'])->name('user.admin');
    Route::get('/admin/users/delete/{id}', [UserAdminController::class, 'deleteUser'])->name('user.admin.delete');
});

// Rutas para usuarios (usuaris) - API
Route::prefix('usuarios')->name('users.')->group(function () {
    Route::get('/', [UsuariController::class, 'index'])->name('index');
    Route::get('/{id}', [UsuariController::class, 'show'])->name('show');
    Route::post('/', [UsuariController::class, 'store'])->name('store');
    Route::put('/{id}', [UsuariController::class, 'update'])->name('update');
    Route::delete('/{id}', [UsuariController::class, 'destroy'])->name('destroy');
});

// Rutas para artículos (articles)
Route::prefix('articulos')->name('articles.')->group(function () {
    // Vistas principales de artículos (accesibles por todos)
    Route::get('/', [ArticleController::class, 'index'])->name('index');
    Route::post('/search-ajax', [ArticleController::class, 'searchAjax'])->name('search.ajax');
    
    // Rutas que requieren autenticación
    Route::middleware('auth')->group(function () {
        // Creación de artículos
        Route::get('/crear', [ArticleController::class, 'create'])->name('create');
        Route::get('/crear-qr', [ArticleController::class, 'createQr'])->name('create.qr');
        Route::post('/guardar', [ArticleController::class, 'store'])->name('store');
        
        // Carga y procesamiento de QR
        Route::get('/cargar-qr', [ArticleController::class, 'uploadQr'])->name('upload.qr');
        Route::post('/procesar-qr', [ArticleController::class, 'processQr'])->name('process.qr');
        
        // Edición y eliminación de artículos
        Route::get('/{id}/editar', [ArticleController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ArticleController::class, 'update'])->name('update');
        Route::delete('/{id}', [ArticleController::class, 'delete'])->name('delete');
        
        // Funcionalidad QR
        Route::get('/{id}/qr', [ArticleController::class, 'generateQr'])->name('qr');
    });
    
    // Rutas para filtrar artículos de usuario específico
    Route::get('/usuario/{userID}', [ArticleController::class, 'userArticles'])->name('user');
});
