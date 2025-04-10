<?php

namespace App\Http\Controllers;

use App\Models\Usuari;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class AuthController extends Controller
{
    /**
     * Mostrar la vista de inicio de sesión
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Procesar la solicitud de inicio de sesión
     */
    public function login(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'ERROR - EMAIL NO POT ESTAR BUIT!!',
            'email.email' => 'ERROR - EL FORMAT DEL EMAIL NO ES CORRECTE!!',
            'password.required' => 'ERROR - EL PASSWORD NO POT ESTAR BUIT!!',
        ]);

        $email = $request->input('email');
        $password = $request->input('password');

        // Verificar reCAPTCHA si es necesario
        if (Session::get('login_attempts', 0) >= 3) {
            $recaptchaResponse = $request->input('g-recaptcha-response');
            
            if (empty($recaptchaResponse)) {
                return redirect()->back()
                    ->with('error', 'ERROR - COMPLETA EL reCAPTCHA!!')
                    ->withInput($request->except('password'));
            }

            $secretKey = '6LeCepAqAAAAAIDPbIiuswfS3LFWWk4oWfsXOUCu';
            $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$recaptchaResponse");
            $responseKeys = json_decode($response, true);

            if (intval($responseKeys["success"]) !== 1) {
                return redirect()->back()
                    ->with('error', 'ERROR - COMPLETA EL reCAPTCHA!!')
                    ->withInput($request->except('password'));
            }
        }

        // Buscar usuario por email
        $usuario = Usuari::where('email', $email)->first();

        if (!$usuario) {
            Session::put('login_attempts', Session::get('login_attempts', 0) + 1);
            return redirect()->back()
                ->with('error', 'ERROR - AQUEST CORREU NO EXISTEIX!!')
                ->withInput($request->except('password'));
        }

        // Verificar contraseña
        if (!Hash::check($password, $usuario->Contrasenya)) {
            Session::put('login_attempts', Session::get('login_attempts', 0) + 1);
            return redirect()->back()
                ->with('error', 'ERROR - PASSWORD INCORRECTE!!')
                ->withInput($request->except('password'));
        }

        // Inicio de sesión exitoso
        Session::put('userID', $usuario->ID);
        Session::put('username', $usuario->Nom_usuari);
        Session::put('admin', $usuario->admin);
        Session::put('login_attempts', 0); // Reiniciar intentos
        
        // Usar el sistema de autenticación de Laravel además de las sesiones personalizadas
        Auth::login($usuario);

        return redirect()->route('articles.index')
            ->with('success', 'SESSIÓ INICIADA CORRECTAMENT!');
    }

    /**
     * Procesar el inicio de sesión con GitHub
     */
    public function redirectToGitHub()
    {
        return \Socialite::driver('github')->redirect();
    }

    /**
     * Callback después de autenticación con GitHub
     */
    public function handleGitHubCallback()
    {
        try {
            $user = \Socialite::driver('github')->user();
            
            // Buscar si existe o crear un nuevo usuario
            $existingUser = Usuari::where('email', $user->email)->first();
            
            if ($existingUser) {
                // Usuario existe, iniciar sesión
                Session::put('userID', $existingUser->ID);
                Session::put('username', $existingUser->Nom_usuari);
                Session::put('admin', $existingUser->admin);
                Session::put('login_attempts', 0);
                
                return redirect()->route('articles.index')
                    ->with('success', 'SESSIÓ INICIADA CORRECTAMENT!');
            } else {
                // Guardar información para el registro
                Session::put('github_email', $user->email);
                Session::put('github_name', $user->name);
                
                return redirect()->route('register', ['github' => 1]);
            }
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Error al autenticar con GitHub: ' . $e->getMessage());
        }
    }

    /**
     * Procesar el inicio de sesión con Google
     */
    public function redirectToGoogle()
    {
        return \Socialite::driver('google')->redirect();
    }

    /**
     * Callback después de autenticación con Google
     */
    public function handleGoogleCallback()
    {
        try {
            $user = \Socialite::driver('google')->user();
            
            // Buscar si existe o crear un nuevo usuario
            $existingUser = Usuari::where('email', $user->email)->first();
            
            if ($existingUser) {
                // Usuario existe, iniciar sesión
                Session::put('userID', $existingUser->ID);
                Session::put('username', $existingUser->Nom_usuari);
                Session::put('admin', $existingUser->admin);
                Session::put('login_attempts', 0);
                
                return redirect()->route('articles.index')
                    ->with('success', 'SESSIÓ INICIADA CORRECTAMENT!');
            } else {
                // Guardar información para el registro
                Session::put('google_email', $user->email);
                Session::put('google_name', $user->name);
                
                return redirect()->route('register', ['google' => 1]);
            }
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Error al autenticar con Google: ' . $e->getMessage());
        }
    }

    /**
     * Cerrar sesión de usuario
     */
    public function logout()
    {
        Session::flush();
        return redirect()->route('login')->with('success', 'Has cerrado sesión correctamente');
    }

    /**
     * Mostrar formulario de registro
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Procesar registro de usuario
     */
    public function register(Request $request)
    {
        // Store input values in session for form repopulation in case of validation failure
        Session::put('nom', $request->Nom_usuari);
        Session::put('email', $request->Email);
        
        // Validación para requisitos de contraseña fuerte
        $password_regex = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/";
        
        $messages = [
            'Nom_usuari.required' => 'ERROR - EL NOM NO POT ESTAR BUIT!!',
            'Email.required' => 'ERROR - EMAIL NO POT ESTAR BUIT!!',
            'Email.email' => 'ERROR - EL FORMAT DEL EMAIL NO ES CORRECTE!!',
            'Email.unique' => 'ERROR - JA EXISTEIX UN COMPTE AMB AQUEST EMAIL!!',
            'Contrasenya.required' => 'ERROR - EL PASSWORD NO POT ESTAR BUIT!!',
            'Contrasenya.confirmed' => 'ERROR - LES PASSWORDS NO COINCIDEIXEN!!',
            'Contrasenya.regex' => 'ERROR - PASWORD MOLT DEBIL!!',
        ];

        $request->validate([
            'Nom_usuari' => 'required|string|max:255',
            'Email' => 'required|string|email|max:255|unique:usuaris,Email',
            'Contrasenya' => ['required', 'string', 'confirmed', "regex:$password_regex"],
        ], $messages);

        $user = Usuari::inserirUsuari(
            $request->Nom_usuari,
            $request->Email,
            $request->Contrasenya
        );

        Auth::login($user);
        
        // Clear session values after successful registration
        Session::forget(['nom', 'email']);

        return redirect()->route('articles.index')->with('success', 'REGISTRAT CORRECTAMENT!!');
    }

    /**
     * Mostrar perfil de usuario
     */
    public function profile()
    {
        return view('auth.profile');
    }

    /**
     * Actualizar perfil de usuario
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'Nom_usuari' => 'required|string|max:255',
        ]);

        Usuari::updateProfile(Auth::id(), $request->Nom_usuari);

        return redirect()->route('profile')->with('success', 'Perfil actualizado correctamente.');
    }

    /**
     * Actualizar contraseña de usuario
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'Contrasenya' => ['required', 'confirmed', Password::min(6)],
        ]);

        // Verificar contraseña actual
        $user = Auth::user();
        if (!Hash::check($request->current_password, $user->Contrasenya)) {
            return back()->withErrors([
                'current_password' => 'La contraseña actual no es correcta.',
            ]);
        }

        // Actualizar contraseña
        Usuari::updatePasswd(Auth::id(), $request->Contrasenya);

        return redirect()->route('profile')->with('password-success', 'Contraseña actualizada correctamente.');
    }

    /**
     * Mostrar formulario de recuperación de contraseña
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Enviar enlace de restablecimiento de contraseña
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'forgotEmail' => 'required|email',
        ]);

        $usuario = Usuari::where('Email', $request->forgotEmail)->first();

        if (!$usuario) {
            return back()->with('error', 'ERROR - AQUEST CORREU NO EXISTEIX!!');
        }

        $token = Str::random(64);

        \DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->forgotEmail],
            [
                'token' => $token,
                'created_at' => now()
            ]
        );

        \Mail::send('auth.emails.resetpassword', ['token' => $token, 'email' => $request->forgotEmail], function($message) use($request){
            $message->to($request->forgotEmail);
            $message->subject('Recuperación de contraseña');
            $message->from(config('mail.from.address'), config('mail.from.name'));
        });

        return back()->with('status', 'REVISA EL TEU CORREU');
    }

    /**
     * Mostrar formulario de restablecimiento de contraseña
     */
    public function showResetPasswordForm($token)
    {
        return view('auth.changeforgotpassword', ['token' => $token]);
    }

    /**
     * Restablecer la contraseña
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required',
            'token' => 'required'
        ]);

        $updatePassword = \DB::table('password_reset_tokens')
            ->where([
                'email' => $request->email,
                'token' => $request->token
            ])
            ->first();

        if (!$updatePassword) {
            return back()->withInput()->with('error', 'Token inválido!');
        }

        $user = Usuari::where('Email', $request->email)
                      ->update(['Contrasenya' => Hash::make($request->password)]);

        \DB::table('password_reset_tokens')->where(['email'=> $request->email])->delete();

        return redirect()->route('login')->with('success', 'Contraseña actualizada correctamente!');
    }
}
