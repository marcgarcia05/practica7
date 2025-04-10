<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Usuari;
use Illuminate\Support\Facades\Mail;

class PasswordController extends Controller
{
    /**
     * Mostrar formulario para solicitar restablecimiento de contraseña
     */
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Enviar enlace de restablecimiento por correo
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'forgotEmail' => 'required|email',
        ]);

        $usuario = Usuari::where('Email', $request->forgotEmail)->first();

        if (!$usuario) {
            return back()->with('error', 'ERROR - AQUEST CORREU NO EXISTEIX!!');
        }

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->forgotEmail],
            [
                'token' => $token,
                'created_at' => now()
            ]
        );

        Mail::send('auth.emails.resetpassword', ['token' => $token, 'email' => $request->forgotEmail], function($message) use($request){
            $message->to($request->forgotEmail);
            $message->subject('Recuperación de contraseña');
            $message->from(config('mail.from.address'), config('mail.from.name'));
        });

        return back()->with('status', 'REVISA EL TEU CORREU');
    }

    /**
     * Mostrar formulario para cambiar la contraseña
     */
    public function showResetForm($token)
    {
        // Get the email from the request query
        $email = request('email');
        
        // Validate token exists in password_reset_tokens table
        $tokenRecord = DB::table('password_reset_tokens')
            ->where('token', $token)
            ->where('email', $email)
            ->first();
            
        if (!$tokenRecord) {
            return redirect()->route('password.request')
                ->with('error', 'Aquest enllaç de restabliment no és vàlid o ha caducat.');
        }
        
        return view('auth.changeforgotpassword', [
            'token' => $token,
            'email' => $email
        ]);
    }

    /**
     * Procesar cambio de contraseña
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required',
            'token' => 'required'
        ]);

        $updatePassword = DB::table('password_reset_tokens')
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

        DB::table('password_reset_tokens')->where(['email'=> $request->email])->delete();

        return redirect()->route('login')->with('success', 'Contraseña actualizada correctamente!');
    }
}
