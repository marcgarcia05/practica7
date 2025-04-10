<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /**
     * Mostrar el formulario de login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Procesar el intento de login.
     */
    public function login(Request $request)
    {
        // Inicializar contador de intentos de login
        if (!Session::has('login_attempts')) {
            Session::put('login_attempts', 0);
        }

        // Validar los datos del formulario
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Verificar reCAPTCHA si es necesario
        if (Session::get('login_attempts', 0) >= 3) {
            $recaptchaResponse = $request->input('g-recaptcha-response');
            $secretKey = '6LeCepAqAAAAAIDPbIiuswfS3LFWWk4oWfsXOUCu';
            $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$recaptchaResponse");
            $responseKeys = json_decode($response, true);

            if (intval($responseKeys["success"]) !== 1) {
                Session::flash('error', 'ERROR - COMPLETA EL reCAPTCHA!!');
                return redirect()->route('login');
            }
        }

        // Intentar autenticar al usuario
        if (Auth::attempt($request->only('email', 'password'))) {
            // Autenticación exitosa
            Session::put('login_attempts', 0); // Reset login attempts
            Session::flash('success', 'SESSIÓ INICIADA CORRECTAMENT!');
            return redirect()->intended(route('home'));
        }

        // Incrementar contador de intentos fallidos
        Session::put('login_attempts', Session::get('login_attempts', 0) + 1);
        
        // Autenticación fallida
        throw ValidationException::withMessages([
            'email' => ['ERROR - Les credencials no són correctes.'],
        ]);
    }

    /**
     * Cerrar sesión del usuario
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('home');
    }
    
    /**
     * Redirección a Google para autenticación
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
    
    /**
     * Procesar la respuesta de Google después de la autenticación
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Buscar usuario por email o crear uno nuevo
            $user = User::where('email', $googleUser->email)->first();
            
            if (!$user) {
                // Si no existe, registramos al usuario
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'password' => Hash::make(rand(1, 10000)), // Contraseña aleatoria segura
                ]);
            }
            
            // Iniciar sesión
            Auth::login($user);
            Session::flash('success', 'SESSIÓ INICIADA CORRECTAMENT AMB GOOGLE!');
            return redirect()->route('home');
            
        } catch (\Exception $e) {
            Session::flash('error', 'Error al autenticar amb Google: ' . $e->getMessage());
            return redirect()->route('login');
        }
    }
    
    /**
     * Redirección a GitHub para autenticación
     */
    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
    }
    
    /**
     * Procesar la respuesta de GitHub después de la autenticación
     */
    public function handleGithubCallback()
    {
        try {
            $githubUser = Socialite::driver('github')->user();
            
            // Buscar usuario por email o crear uno nuevo
            $user = User::where('email', $githubUser->email)->first();
            
            if (!$user) {
                // Si no existe, registramos al usuario
                $user = User::create([
                    'name' => $githubUser->name ?? $githubUser->nickname,
                    'email' => $githubUser->email,
                    'password' => Hash::make(rand(1, 10000)), // Contraseña aleatoria segura
                ]);
            }
            
            // Iniciar sesión
            Auth::login($user);
            Session::flash('success', 'SESSIÓ INICIADA CORRECTAMENT AMB GITHUB!');
            return redirect()->route('home');
            
        } catch (\Exception $e) {
            Session::flash('error', 'Error al autenticar amb GitHub: ' . $e->getMessage());
            return redirect()->route('login');
        }
    }
}
