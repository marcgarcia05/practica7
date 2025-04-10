<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Models\Usuari;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    public function showForgotPasswordForm()
    {
        return view('auth.forgotpassword');
    }

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

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->forgotEmail],
            [
                'token' => $token,
                'created_at' => Carbon::now()
            ]
        );

        Mail::send('auth.emails.resetpassword', ['token' => $token], function($message) use($request){
            $message->to($request->forgotEmail);
            $message->subject('Recuperación de contraseña');
            $message->from('m.garcia5@sapalomera.cat', 'Marc');
        });

        return back()->with('status', 'REVISA EL TEU CORREU');
    }

    public function showResetForm($token)
    {
        return view('auth.changeforgotpassword', ['token' => $token]);
    }

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
            return back()->withInput()->with('error', 'Invalid token!');
        }

        $user = Usuari::where('Email', $request->email)->update(['Contrasenya' => bcrypt($request->password)]);

        DB::table('password_reset_tokens')->where(['email'=> $request->email])->delete();

        return redirect()->route('login')->with('status', 'Contraseña actualizada correctamente!');
    }
}
